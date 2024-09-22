<template>
    <div class="login-area" style="height: 100vh;">
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-lg-4">
                    <div class="login-box">
                        <div class="login-top">
                            <h1 class="title text-center">Admin Paneline <br> Giriş Yap</h1>
                        </div>
                        <div class="login-form">
                            <form @submit.prevent="login">
                                <div class="form-input">
                                    <label for="">Kullanıcı Adı</label>
                                    <input type="text" class="form-control" v-model="formData.username">
                                </div>
                                <div class="form-input">
                                    <label for="">Şifre</label>
                                    <input type="password" class="form-control" v-model="formData.password">
                                </div>
                                <div class="login-btn-area">
                                    <button type="submit" class="site-btn btn login-btn">Giriş Yap</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    export default {
        name: "LoginComponent",
        data() {
            return {
                formData: {
                    username: '',
                    password: '',
                }
            }
        },
        methods: {
            async login() {
                try {
                    const response = await axios.post('/admin/giris', {
                        user_name: this.formData.username,
                        password: this.formData.password
                    })
                    window.location.href = '/admin';
                    return;
                } catch (error) {
                    this.errorMessage = 'Giriş Bilgileri Geçersiz.';
                    console.error(error.response.data.message);
                }
            }
        }
    }

</script>
<style>

</style>
