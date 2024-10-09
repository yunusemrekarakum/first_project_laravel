<template>
    <div class="account-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="account-box">
                        <span class="account-name" v-if="user_info">{{
                            user_info.name
                        }}</span>
                        <ul class="account-list">
                            <li class="active"><a href="">Hesabım</a></li>
                            <li>
                                <button
                                    @click="logout"
                                    class="btn-none btn p-0 w-100 text-start"
                                >
                                    Çıkış
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="account-box">
                        <form @submit.prevent="handleSubmit">
                            <span class="account-title"
                                >Hesap Bilgilerimi Düzenle</span
                            >
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Adınız Soyadınız</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-if="user_info"
                                            :placeholder="user_info.name"
                                            v-model="formData.name"
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Email adresi</label>
                                        <Field
                                            name="email"
                                            as="input"
                                            v-if="user_info"
                                            :placeholder="user_info.email"
                                            v-model="formData.email"
                                            class="form-control"
                                        />
                                        <ErrorMessage
                                            name="email"
                                            class="text-danger"
                                        />
                                    </div>
                                </div>
                            </div>
                            <span class="account-title">Şifre Değiştir</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Eski Şifre</label>
                                        <Field
                                            name="old_password"
                                            as="input"
                                            type="password"
                                            v-model="formData.old_password"
                                            class="form-control"
                                            rules="min:6"
                                        />
                                        <ErrorMessage
                                            name="password"
                                            class="text-danger"
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Yeni Şifre</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            v-model="formData.password"
                                        />
                                    </div>
                                </div>
                            </div>
                            <span class="account-title">Profil Resmi</span>
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="form-input">
                                        <input
                                            type="file"
                                            class="form-control"
                                            @change="handleFileUpload"
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="profile-photo">
                                        <img
                                            :src="user_info.profile_image"
                                            alt=""
                                            v-if="
                                                user_info &&
                                                user_info.profile_image
                                            "
                                        />
                                        <img :src="userphoto" alt="" v-else />
                                    </div>
                                </div>
                            </div>
                            <div class="login-btn-area">
                                <button type="submit" class="site-btn w-100">
                                    Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { inject, onMounted, ref } from "vue";
import router from "../router/index.js";
import { useToast } from "vue-toastification";
import axios from "axios";
import { Field, ErrorMessage, useForm } from "vee-validate";
import * as yup from "yup";
export default {
    name: "AccountComponent",
    setup() {
        const toast = useToast();
        const $session = inject("$vsession");
        const token = $session.get("token");
        const user_info = ref(null);
        const profile_image = ref(null);
        const formData = ref({
            name: null,
            email: null,
            old_password: null,
            password: null,
            profile_image: null,
        });
        const validationSchema = yup.object().shape({
            email: yup
                .string()
                .email("Geçerli bir Email adresi girin")
                .notRequired(),
            old_password: yup.string().notRequired().nullable(),
            password: yup.string().min(6, "Şifre en az 6 karakter olmalıdır"),
        });

        const { handleSubmit, errors } = useForm({
            validationSchema,
        });
        const user_get_info = async () => {
            const userquery = `
                query {
                    user {
                        email
                        name
                        profile_image
                    }
                }`;
            const response = await axios.post(
                "/graphql",
                { query: userquery },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        "Content-Type": "application/json",
                    },
                }
            );
            user_info.value = response.data.data.user;
        };
        const handleFileUpload = (e) => {
            profile_image.value = e.target.files[0];
        };
        const onSubmit = async () => {
            if (
                Object.values(formData.value).some(
                    (value) => value !== null && value !== ""
                )
            ) {
                errors.value = {};
                try {
                    const formDataToSend = new FormData();
                    const query = `
                    mutation ($file: Upload, $name: String, $email: String, $password: String, $old_password: String) {
                        updateUser(
                            name: $name,
                            email: $email,
                            password: $password,
                            old_password: $old_password,
                            profile_image: $file
                        ) {
                            id
                            name
                            email
                            profile_image
                        }
                    }
                `;
                    const operations = {
                        query: query,
                        variables: {
                            name: formData.value.name,
                            email: formData.value.email,
                            password: formData.value.password,
                            old_password: formData.value.old_password,
                            file: null,
                        },
                    };
                    formDataToSend.append(
                        "operations",
                        JSON.stringify(operations)
                    );
                    const map = {
                        0: ["variables.file"],
                    };
                    formDataToSend.append("map", JSON.stringify(map));
                    if (profile_image.value) {
                        formDataToSend.append("0", profile_image.value);
                    }
                    const response = await axios.post(
                        "/graphql",
                        formDataToSend,
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                    if (
                        response.data.errors &&
                        response.data.errors.length > 0
                    ) {
                        toast.error(
                            response.data.errors[0].extensions.debugMessage
                        );
                    } else {
                        toast.success("Hesap Bilgileri Güncellendi");
                        window.location.reload();
                    }
                } catch (error) {
                    toast.error(error.response.data);
                }
            } else {
                toast.error("Form Boş Gönderilemez");
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
        onMounted(() => {
            user_get_info();
        });
        const logout = () => {
            $session.destroy("token");
            router.push({
                name: "Home",
            });
        };
        return {
            user_info,
            formData,
            userphoto: null,
            handleSubmit: handleSubmitWithValidation,
            errors,
            logout,
        };
    },
};
</script>
<style lang=""></style>
