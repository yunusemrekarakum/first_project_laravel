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
                            <form @submit.prevent="handleSubmit">
                                <div class="form-input">
                                    <label for="">Kullanıcı Adı</label>
                                    <input type="text" class="form-control" v-model="formData.user_name">
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
import {inject, ref} from "vue";
import axios from 'axios';
import {useRouter} from 'vue-router';

export default {
    name: "LoginComponent",
    setup() {
        const $session = inject('$vsession');
        const formData = ref({
            user_name: '',
            password: '',
        });
        const router = useRouter();
        const handleSubmit = async () => {
            const mutation = `
                mutation {
                   adminLogin(user_name: "${formData.value.user_name}", password: "${formData.value.password}") {
                        token
                    }
                }
                `;
            try {
                const response = await axios.post('/graphql', {query: mutation});
                const token = response.data.data.adminLogin.token;
                $session.set('token', token);
                const expiryDuration = 7200000; // 7200000 ms = 2 saat
                const expiryTime = new Date(new Date().getTime() + expiryDuration); // 2 saat sonrası
                $session.set('token', token);
                $session.set('token_expiry', expiryTime.toISOString());
                router.push("Admin")
            } catch (error) {
                console.error(error);
                // Hata durumunda kullanıcıya bir mesaj göstermek için bir yöntem ekleyebilirsiniz
            }
        };

        return {
            formData,
            handleSubmit // Burada handleSubmit'i döndürüyoruz
        };
    }
}

</script>
<style>

</style>
