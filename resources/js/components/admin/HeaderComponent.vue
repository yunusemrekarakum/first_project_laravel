<template>
    <header class="header-area">
        <div class="container-fluid">
            <div class="header-item">
                <div class="logo">
                    <router-link to="/admin">
                        <img :src="logoUrl" alt="">
                    </router-link>
                </div>
                <div class="header-links">
                    <ul>
                        <li>
                            <router-link to="/admin">Ürünler</router-link>
                            <ul class="sub-menu">
                                <li><router-link to="/admin/urun-ekle">Ekle</router-link></li>
                            </ul>
                        </li>
                        <li>
                            <router-link to="/admin/kategori-listele">Kategoriler</router-link>
                            <ul class="sub-menu">
                                <li><router-link to="/admin/kategori-ekle">Ekle</router-link></li>
                            </ul>
                        </li>
                        <li>
                            <router-link to="/admin-listele">Admin</router-link>
                            <ul class="sub-menu">
                                <li><router-link to="/admin-ekle">Ekle</router-link></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="admin-account">
                    <div class="admin-account-content">
                        <div class="user-photo">
                            <router-link to="/admin/hesabim">
                                <img :src="user_info.profile_image" alt=""
                                     v-if="user_info && user_info.profile_image">
                                <img :src="userphoto" alt="" v-else>
                            </router-link>
                        </div>
                        <div class="user-info">
                            <router-link to="/admin/hesabim">
                                <span class="user-name" v-if="user_info">{{ user_info.name }}</span>
                                <div class="icon">
                                </div>
                            </router-link>
                        </div>
                    </div>
                    <ul class="admin-account-other-link">
                        <li>
                            <router-link to="/admin/hesabim">
                                Profil
                            </router-link>
                        </li>

                        <li>
                            <button @click="logout">Çıkış Yap</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</template>
<script>
import {inject, onMounted, ref} from "vue";
import router from "../../router/index.js";
import axios from "axios";

export default {
    data() {
        return {
            logoUrl: 'http://localhost/assets/img/logo.png',
            userphoto: 'http://localhost/assets/img/user-photo.png',
        };
    },
    name: "HeaderComponent",
    setup() {
        const $session = inject('$vsession');
        const user_info = ref(null);
        const logout = () => {
            $session.destroy("token");
            router.push({name: "AdminLogin"})
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
            const response = await axios.post('/graphql', {query: userquery}, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            user_info.value = response.data.data.user;
        };
        onMounted(() => {
            UserInfoGet();
        });
        return {
            logout,
            UserInfoGet,
            user_info,
        };
    },

}

</script>
<style>

</style>
