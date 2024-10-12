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
                            Güncelle
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
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { Field, ErrorMessage, useForm } from "vee-validate";
import * as yup from "yup";

export default {
    setup() {
        const formData = ref({
            title: "",
        });
        const router = useRouter();
        const categoryId = ref(router.currentRoute.value.params.id);
        const toast = useToast();
        const validationSchema = yup.object({
            title: yup.string().required("Ürün ismi girimiz."),
        });
        const { handleSubmit, errors } = useForm({
            validationSchema,
            initialValues: formData,
        });

        const handleSubmitWithValidation = async () => {
            const isValid = await handleSubmit(CategoryEdit)();
            if (!isValid) {
                for (const key in errors.value) {
                    if (errors.value[key]) {
                        toast.error(errors.value[key]);
                    }
                }
            }
        };
        const CategoryEdit = async () => {
            const title = formData.value.title;
            try {
                const response = await axios.post("/graphql", {
                    query: `
                        mutation {
                            EditCategories(id:"${categoryId.value}",title:"${title}") {
                                id
                            }
                        }
                    `,
                });
                if (response.data.errors && response.data.errors.length > 0) {
                    toast.error(response.data.errors[0].message);
                } else {
                    toast.success("Kategori Güncellendi");
                }
            } catch (error) {
                toast.error(error);
            }
        };
        const CategoryGet = async () => {
            const response = await axios.post("/graphql", {
                query: `
                    mutation {
                        EditListCategory(id: ${categoryId.value}) {
                            title
                        }
                    }
                `,
            });
            formData.value = response.data.data.EditListCategory;
        };
        onMounted(() => {
            CategoryGet();
        });
        return {
            formData,
            handleSubmit: handleSubmitWithValidation,
        };
    },
};
</script>
<style></style>
