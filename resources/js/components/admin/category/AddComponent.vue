<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Kategori Ekle</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="handleSubmit">
                    <div class="input-area">
                        <label for="">Kategori İsmi</label>
                        <Field
                            name="title"
                            as="input"
                            rules="required"
                            type="text"
                            class="form-control"
                            v-model="formData.title"
                        />
                        <ErrorMessage name="title" class="text-danger" />
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
            title: "",
        });
        const toast = useToast();
        const validationSchema = yup.object({
            title: yup.string().required("Ürün ismi girimiz."),
        });
        const { handleSubmit, errors } = useForm({
            validationSchema,
            initialValues: formData,
        });

        const handleSubmitWithValidation = async () => {
            const isValid = await handleSubmit(CategoryAdd)();
            if (!isValid) {
                for (const key in errors.value) {
                    if (errors.value[key]) {
                        toast.error(errors.value[key]);
                    }
                }
            }
        };
        const CategoryAdd = async () => {
            const title = formData.value.title;
            try {
                const response = await axios.post("/graphql", {
                    query: `
                        mutation {
                            AddCategories(title:"${title}") {
                                id
                                title
                            }
                        }
                    `,
                });
                console.log(response.data.data.AddCategories);
                if (response.data.errors && response.data.errors.length > 0) {
                    toast.error(response.data.errors[0].message);
                } else {
                    toast.success("Ürün Eklendi");
                }
            } catch (error) {
                toast.error(error);
            }
        };
        return {
            formData,
            handleSubmit: handleSubmitWithValidation,
        };
    },
};
</script>
<style></style>
