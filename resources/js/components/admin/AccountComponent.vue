<template>
    <div class="account-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="account-box">
                        <span class="account-name">Yunus Emre Karakum</span>
                        <ul class="account-list">
                            <li class="active"><a href="">Hesabım</a></li>
                            <li><a href="">Çıkış</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="account-box">
                        <form @submit.prevent="updateAdmin">
                            <span class="account-title">Hesap Bilgilerimi Düzenle</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Adınız Soyadınız</label>
                                        <input type="text" class="form-control"
                                               v-if="admin_info" :placeholder="admin_info.name_surname"
                                               v-model="formData.name_surname">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Kullanıcı Adı</label>
                                        <input type="text" class="form-control"
                                               v-if="admin_info" :placeholder="admin_info.user_name"
                                               v-model="formData.user_name">
                                    </div>
                                </div>
                            </div>
                            <span class="account-title">Şifre Değiştir</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Eski Şifre</label>
                                        <input type="text" class="form-control"
                                               v-model="formData.old_password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Yeni Şifre</label>
                                        <input type="text" class="form-control"
                                               v-model="formData.password">
                                    </div>
                                </div>
                            </div>
                            <span class="account-title">Profil Resmi</span>
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="form-input">
                                        <input type="file" class="form-control"
                                               @change="formData.profile_image">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="profile-photo">
                                        <img :src="admin_info.profile_image" alt=""
                                             v-if="admin_info && admin_info.profile_image">
                                        <img :src="userphoto" alt="" v-else>
                                    </div>
                                </div>
                            </div>
                            <div class="login-btn-area">
                                <button type="submit" class="site-btn w-100">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {inject, onMounted, ref} from "vue";
import router from "../../router/index.js";
import axios from "axios";

export default {
    name: "AccountComponent",
    data() {
        return {
            userphoto: '../assets/img/user-photo.png',
        };
    },
    setup() {
        const $session = inject('$vsession');
        const token = $session.get("token")
        const admin_info = ref(null);
        const admin_get_info = async () => {
            const adminquery = `
                query {
                    admin {
                        user_name
                        name_surname
                        profile_image
                    }
                }`;
            const response = await axios.post('/graphql', {query: adminquery}, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            admin_info.value = response.data.data.admin;
        };
        const handleFileUpload = (event) => {
            const file = event.target.files[0];
            formData.value.profile_image = file; // File nesnesini sakla
        };
        const updateAdmin = async () => {
            try {
                mutation = `
                    mutation {
                        updateAdmin(
                            id: "${admin_info.value.id}",
                            name_surname: ${formData.value.name_surname ? `"${formData.value.name_surname}"` : null},
                            user_name: ${formData.value.user_name ? `"${formData.value.user_name}"` : null},
                            password: ${formData.value.password ? `"${formData.value.password}"` : null},
                            profile_image: ${formData.value.profile_image ? `"${formData.value.profile_image.name}"` : null}
                        ) {
                            id
                            name_surname
                            user_name
                            profile_image
                        }
                    }
                `;
                const formDataToSend = new FormData();
                const response = await axios.post("/graphql", {query: mutation}, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                })
                console.log(response)
            } catch (error) {
                console.log(error)
            }
        };
        onMounted(() => {
            admin_get_info();
        });
        return {
            admin_info,
            formData: {
                'name_surname': null,
                'user_name': null,
                'old_passwod': null,
                'password': null,
                'profile_image': null
            }
        };
    }
}

</script>
