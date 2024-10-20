<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Bildirim Gönder</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="handleSubmit">
                    <div class="input-area">
                        <label for="">Kullanıcı Seç</label>
                        <Field
                            name="user_id"
                            as="select"
                            rules="required"
                            class="form-select"
                            v-model="formData.user_id"
                        >
                            <option selected disabled>Kullanıcı Seçiniz</option>
                            <option v-for="uservalue in getallusers"
                                v-bind:key="uservalue.id"
                                :value="uservalue.id">
                                {{ uservalue.email }}
                            </option>
                        </Field>
                        <ErrorMessage name="user_id" class="text-danger" />
                    </div>
                    <div class="input-area">
                        <label for="">Gönderilecek Bildirimi Giriniz.</label>
                        <Field
                            name="notification"
                            as="input"
                            rules="required"
                            class="form-control"
                            v-model="formData.notification"
                        />
                        <ErrorMessage name="notification" class="text-danger" />
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-outline-success">
                            Ekle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { Field, ErrorMessage, useForm } from "vee-validate";
import * as yup from "yup";

export default {
    setup() {
        const formData = ref({
            notification: "",
            user_id: "",
        });
        const getallusers = ref([]);
        const toast = useToast();
        const validationSchema = yup.object({
            notification: yup.string().required("Bildirim Giriniz."),
            user_id: yup.string().required("Kullanıcı Seçiniz."),
        });
        const { handleSubmit, errors } = useForm({
            validationSchema,
            initialValues: formData,
        });

        const handleSubmitWithValidation = async () => {
            const isValid = await handleSubmit(notification_send)();
            if (!isValid) {
                for (const key in errors.value) {
                    if (errors.value[key]) {
                        toast.error(errors.value[key]);
                    }
                }
            }
        };
        const notification_send = async () => {
            console.log(formData.value.user_id);
            
            try {
                const response = await axios.post("/graphql", {
                    query: `
                        mutation {
                            notification_send(notification:"${formData.value.notification}", user_id: ${formData.value.user_id})
                        }
                    `,
                });
                console.log(response.data.data.AddCategories);
                if (response.data.errors && response.data.errors.length > 0) {
                    toast.error(response.data.errors[0].message);
                } else {
                    toast.success("Bildirim Gönderildi");
                }
            } catch (error) {
                toast.error(error);
            }
        };
        const all_user = async () => {
            const query = `
                query{
                    getAllUser {
                        id
                        name
                        email
                    }
                }
                `;

            const response = await axios.post("/graphql", {
                query: query,
            });
            getallusers.value = response.data.data.getAllUser;
        };
        onMounted(() => {
            all_user();
        });
        return {
            formData,
            handleSubmit: handleSubmitWithValidation,
            getallusers,
        };
    },
};
</script>
<style></style>
