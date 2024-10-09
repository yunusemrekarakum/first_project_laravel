<template>
    <div class="login-area" style="height: 100vh">
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-lg-4">
                    <div class="login-box">
                        <div class="login-top">
                            <h1 class="title text-center">
                                Admin Paneline <br />
                                Giriş Yap
                            </h1>
                        </div>
                        <div class="login-form">
                            <form @submit.prevent="handleSubmit">
                                <div class="form-input">
                                    <label for="">E-mail Address</label>
                                    <Field
                                        name="email"
                                        as="input"
                                        v-model="formData.email"
                                        class="form-control"
                                        rules="required"
                                    />
                                    <ErrorMessage
                                        name="email"
                                        class="text-danger"
                                    />
                                </div>
                                <div class="form-input">
                                    <label for="">Şifre</label>
                                    <Field
                                        name="password"
                                        as="input"
                                        type="password"
                                        v-model="formData.password"
                                        class="form-control"
                                        rules="required|min:6"
                                    />
                                    <ErrorMessage
                                        name="password"
                                        class="text-danger"
                                    />
                                </div>
                                <div class="login-btn-area">
                                    <button
                                        type="submit"
                                        class="site-btn btn login-btn"
                                    >
                                        Giriş Yap
                                    </button>
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
import { inject, ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { Field, ErrorMessage, useForm } from "vee-validate";
import * as yup from "yup";
export default {
    name: "LoginComponent",
    setup() {
        const toast = useToast();
        const $session = inject("$vsession");
        const formData = ref({
            email: "",
            password: "",
        });
        const router = useRouter();

        const validationSchema = yup.object().shape({
            email: yup.string().required("Email adı boş bırakılamaz").email("Geçerli bir Email adresi girin"),
            password: yup
                .string()
                .required("Şifre boş bırakılamaz")
                .min(6, "Şifre en az 6 karakter olmalıdır"),
        });

        const { handleSubmit, errors } = useForm({
            validationSchema,
        });
        const onSubmit = async () => {
            errors.value = {};

            const mutation = `
                    mutation {
                    userLogin(email: "${formData.value.email}", password: "${formData.value.password}") {
                            token
                        }
                    }
                `;
            try {
                const response = await axios.post("/graphql", {
                    query: mutation,
                });

                if (response.data.data.userLogin) {
                    const token = response.data.data.userLogin.token;
                    $session.set("token", token);
                    const expiryDuration = 7200000; // 7200000 ms = 2 saat
                    const expiryTime = new Date(
                        new Date().getTime() + expiryDuration
                    ); // 2 saat sonrası
                    $session.set("token", token);
                    $session.set("token_expiry", expiryTime.toISOString());
                    // vue-toastification
                    toast.success("Giriş Başarılı!");
                    router.push("Admin");
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
                        toast.error(errors.value[key]);
                    }
                }
            }
        };
        return {
            formData,
            handleSubmit: handleSubmitWithValidation,
            errors,
        };
    },
};
</script>
<style></style>
