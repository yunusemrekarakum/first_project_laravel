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
                                    <Field name="user_name" as="input" v-model="formData.user_name" class="form-control"
                                        rules="required" />
                                    <ErrorMessage name="user_name" class="text-danger" />
                                </div>
                                <div class="form-input">
                                    <label for="">Şifre</label>
                                    <Field name="password" as="input" type="password" v-model="formData.password"
                                        class="form-control" rules="required|min:6" />
                                    <ErrorMessage name="password" class="text-danger" />
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
    import {
        inject,
        ref
    } from "vue";
    import axios from 'axios';
    import {
        useRouter
    } from 'vue-router';
    import {
        useToast
    } from "vue-toastification";
    import {
        Field,
        ErrorMessage,
        useForm
    } from 'vee-validate';
    import * as yup from 'yup';
    export default {
        name: "LoginComponent",
        setup() {
            const toast = useToast();
            const $session = inject('$vsession');
            const formData = ref({
                user_name: '',
                password: '',
            });
            const router = useRouter();

            const validationSchema = yup.object().shape({
                user_name: yup.string().required('Kullanıcı adı boş bırakılamaz'),
                password: yup.string().required('Şifre boş bırakılamaz').min(6,
                    'Şifre en az 6 karakter olmalıdır')
            });

            const {
                handleSubmit,
                errors
            } = useForm({
                validationSchema
            });
            const onSubmit = async () => {
                errors.value = {};

                const mutation = `
                    mutation {
                    adminLogin(user_name: "${formData.value.user_name}", password: "${formData.value.password}") {
                            token
                        }
                    }
                `;
                try {
                    const response = await axios.post('/graphql', {
                        query: mutation
                    });

                    if (response.data.data.adminLogin) {
                        const token = response.data.data.adminLogin.token;
                        $session.set('token', token);
                        const expiryDuration = 7200000; // 7200000 ms = 2 saat
                        const expiryTime = new Date(new Date().getTime() + expiryDuration); // 2 saat sonrası
                        $session.set('token', token);
                        $session.set('token_expiry', expiryTime.toISOString());
                        // vue-toastification
                        toast.success("Giriş Başarılı!", {
                            position: "top-right",
                            timeout: 5000,
                            closeOnClick: true,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,
                            showCloseButtonOnHover: true,
                            hideProgressBar: true,
                            closeButton: "button",
                            icon: true,
                            rtl: false
                        });
                        router.push("Admin")
                    } else {
                        toast.error(response.data.errors[0].message, {
                            position: "top-right",
                            timeout: 5000,
                            closeOnClick: true,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,
                            showCloseButtonOnHover: true,
                            hideProgressBar: true,
                            closeButton: "button",
                            icon: true,
                            rtl: false
                        });
                    }
                } catch (error) {
                    toast.error(error.response.data.errors, {
                        position: "top-right",
                        timeout: 5000,
                        closeOnClick: true,
                        pauseOnFocusLoss: true,
                        pauseOnHover: true,
                        draggable: true,
                        draggablePercent: 0.6,
                        showCloseButtonOnHover: true,
                        hideProgressBar: true,
                        closeButton: "button",
                        icon: true,
                        rtl: false
                    });
                }
            };
            const handleSubmitWithValidation = async () => {
                const isValid = await handleSubmit(onSubmit)();
                if (!isValid) {
                    for (const key in errors.value) {
                        if (errors.value[key]) {
                            toast.error(`${errors.value[key]}`, {
                                position: "top-right",
                                timeout: 5000,
                                closeOnClick: true,
                                pauseOnFocusLoss: true,
                                draggable: true,
                            });
                        }
                    }
                }
            };

            return {
                formData,
                handleSubmit: handleSubmitWithValidation,
                errors
            };
        }
    }

</script>
<style>

</style>
