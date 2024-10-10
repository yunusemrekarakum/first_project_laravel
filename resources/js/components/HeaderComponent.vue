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
                            <button class="basket-btn">
                                <font-awesome-icon :icon="['fas', 'bag-shopping']" />
                                cart 0
                            </button>
                        </li>
                        <li>
                            <div class="account">
                                <router-link to="/hesabim" v-if="user_info != null">
                                    <img class="profile_image" :src="user_info.profile_image" alt="" v-if="user_info.profile_image">
                                    {{ user_info.name }}
                                </router-link>
                                <router-link to="/giris-yap" v-else>
                                    <font-awesome-icon :icon="['far', 'user']" />
                                    My account
                                </router-link>
                            </div>
                            <div class="account-dropdown" v-if="user_info != null">
                                <ul>
                                    <li><router-link to="/hesabim">Hesabım</router-link></li>
                                    <li><button @click="logout" class="btn-none btn">Çıkış Yap</button></li>
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
    import {
        inject,
        onMounted,
        ref
    } from "vue";
    import router from "../router/index.js";
    import axios from "axios";
    export default {
        data() {
            return {
                logoUrl: '../assets/img/logo.png',
            };
        },
        name: 'HeaderComponent',
        setup() {
            const $session = inject('$vsession');
            const user_info = ref(null);
            const logout = () => {
                $session.destroy("token");
                router.push({
                    name: "Home"
                })
            }
            const UserInfoGet = async () => {
                const token = $session.get("token");
                const userquery = `
                query {
                    user {
                        name
                        email
                        profile_image
                    }
                }`;
                const response = await axios.post('/graphql', {
                    query: userquery
                }, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                })
                
                if(!response.data.data.errors) {
                    user_info.value = response.data.data.user;
                }

            }
            onMounted(() => {
                UserInfoGet();
            });
            return {
                logout,
                UserInfoGet,
                user_info,
            };
        },

    };

</script>

<style scoped>

</style>
