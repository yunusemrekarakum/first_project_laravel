<template>
    <header>
        <div class="container">
            <div class="header-area">
                <div class="logo">
                    <router-link to="/">
                        <img :src="logoUrl" alt="Açıklama" />
                    </router-link>
                </div>
                <div class="user-account">
                    <ul>
                        <li>
                            <span>
                                Bildirimler
                            </span>
                            <ul class="notification">
                                <li
                                    v-for="notifications_val in notifications"
                                    :key="notifications_val.id"
                                >
                                    {{
                                        JSON.parse(notifications_val.data)
                                            .amount
                                    }}
                                </li>
                            </ul>
                        </li>
                        <li>
                            <button class="basket-btn">
                                <font-awesome-icon
                                    :icon="['fas', 'bag-shopping']"
                                />
                                cart 0
                            </button>
                        </li>
                        <li>
                            <div class="account">
                                <router-link
                                    to="/hesabim"
                                    v-if="user_info != null"
                                >
                                    <img
                                        class="profile_image"
                                        :src="user_info.profile_image"
                                        alt=""
                                        v-if="user_info.profile_image"
                                    />
                                    {{ user_info.name }}
                                </router-link>
                                <router-link to="/giris-yap" v-else>
                                    <font-awesome-icon
                                        :icon="['far', 'user']"
                                    />
                                    My account
                                </router-link>
                            </div>
                            <div
                                class="account-dropdown"
                                v-if="user_info != null"
                            >
                                <ul>
                                    <li>
                                        <router-link to="/hesabim"
                                            >Hesabım</router-link
                                        >
                                    </li>
                                    <li>
                                        <button
                                            @click="logout"
                                            class="btn-none btn"
                                        >
                                            Çıkış Yap
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import { inject, onMounted, ref } from "vue";
import router from "../router/index.js";
import axios from "axios";
export default {
    data() {
        return {
            logoUrl: "../assets/img/logo.png",
        };
    },
    name: "HeaderComponent",
    setup() {
        const user_info = ref(null);
        const notifications = ref([]);
        const logout = () => {
            localStorage.removeItem("session");
            router.push({
                name: "Home",
            });
        };
        const UserInfoGet = async () => {
            if (JSON.parse(localStorage.getItem("session")) != null) {
                var token = JSON.parse(localStorage.getItem("session")).token;
            } else {
                return;
            }

            const userquery = `
                query {
                    user {
                        name
                        email
                        profile_image
                    }
                    usernotification{
                        id
                        type
                        notifiable_type
                        notifiable_id
                        data
                        read_at
                        created_at
                    }
                }`;
            const response = await axios.post(
                "/graphql",
                {
                    query: userquery,
                },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        "Content-Type": "application/json",
                    },
                }
            );

            if (!response.data.data.errors) {
                user_info.value = response.data.data.user;
                notifications.value = response.data.data.usernotification;
            }
        };
        onMounted(() => {
            UserInfoGet();
        });
        return {
            logout,
            UserInfoGet,
            user_info,
            notifications
        };
    },
};
</script>

<style scoped></style>
