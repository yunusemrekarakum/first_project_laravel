<template>
    <div class="login-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-box">
                        <div class="login-top">
                            <h1 class="title">Giriş Yap</h1>
                            <p>
                                Lorem ipsum dolor sit, amet consectetur
                                adipisicing elit. Laboriosam, quae!
                            </p>
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
                                    <label for="">Password</label>
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
                                        Login
                                    </button>
                                    <router-link
                                        to="/kayit-ol"
                                        class="site-btn"
                                    >
                                        Create account
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
import { inject, ref, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { Field, ErrorMessage, useForm } from "vee-validate";
import * as yup from "yup";
import { useStore } from "vuex";
export default {
    name: "LoginComponent",
    setup() {
        const toast = useToast();
        const store = useStore();
        const formData = ref({
            email: "",
            password: "",
        });
        const router = useRouter();
        const userRole = computed(() => store.state.userRole);

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
            try {
                await store.dispatch("userLogin", {
                    email: formData.value.email,
                    password: formData.value.password,
                });

                await store.dispatch("fetchUserRole");
                toast.success("Giriş Başarılı!");
                router.push("Home");
            } catch (error) {
                toast.error(error.message);
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
            userRole,
        };
    },
};
</script>
<style></style>
