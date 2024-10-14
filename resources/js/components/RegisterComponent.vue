<template>
    <div class="login-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-box">
                        <div class="login-top">
                            <h1 class="title">Kayıt Ol</h1>
                            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Laboriosam, quae!</p>
                        </div>
                        <div class="login-form">
                            <form @submit.prevent="handleSubmit">
                                <div class="form-input">
                                    <label for="">Full Name</label>
                                    <Field name="name" as="input" type="text" rules="required" class="form-control"
                                        v-model="formData.name" />
                                    <ErrorMessage name="name" class="text-danger" />
                                </div>
                                <div class="form-input">
                                    <label for="">E-mail Address</label>
                                    <Field name="email" as="input" type="email" rules="required|email"
                                        class="form-control" v-model="formData.email" />
                                    <ErrorMessage name="email" class="text-danger" />
                                </div>
                                <div class="form-input">
                                    <label for="">Password</label>
                                    <Field name="password" as="input" type="password" v-model="formData.password"
                                        class="form-control" rules="required|min:6" />
                                    <ErrorMessage name="password" class="text-danger" />
                                </div>
                                <div class="login-btn-area">
                                    <button type="submit" class="site-btn btn login-btn">Register</button>
                                    <router-link to="/giris-yap" class="site-btn">
                                        Login
                                    </router-link>
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
        name: "RegisterComponent",
        setup() {
            const toast = useToast();
            const formData = ref({
                name: '',
                email: '',
                password: '',
            });
            const router = useRouter();

            const validationSchema = yup.object().shape({
                name: yup.string().required('Ad Soyad boş bırakılamaz'),
                email: yup.string().required('E-posta adresi boş bırakılamaz'),
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
                    createUser(email: "${formData.value.email}",
                        name: "${formData.value.name}",
                        password: "${formData.value.password}"
                    ) {
                            token
                            user {
                                name
                                email
                            }
                        }
                    }
                `;
                try {
                    const response = await axios.post('/graphql', {
                        query: mutation
                    });
                    console.log(response);
                    
                    if (response.data.data.createUser) {
                        const token = response.data.data.createUser.token;
                        const expiryDuration = 7200000; // 7200000 ms = 2 saat
                        const expiryTime = new Date(
                            new Date().getTime() + expiryDuration
                        );
                        const sessionData = {
                            token: token,
                            token_time: expiryTime.toString()
                        }
                        localStorage.setItem("session", JSON.stringify(sessionData));
                        toast.success("Kayıt Oluşturuldu");
                        router.push("/")
                    } else {
                        toast.error(response.data.errors[0].message);
                    }
                } catch (error) {
                    toast.error(error.response.data.errors);
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
            };
        }

    }

</script>
<style lang="">

</style>
