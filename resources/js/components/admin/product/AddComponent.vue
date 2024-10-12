<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Ürün Ekle</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="handleSubmit">
                    <div class="input-area">
                        <label for="">Ürün İsmi</label>
                        <Field
                            name="title"
                            as="input"
                            type="text"
                            class="form-control"
                            v-model="formData.title"
                            rules="required"
                        />
                        <ErrorMessage name="title" class="text-danger" />
                    </div>
                    <div class="input-area">
                        <label for="">Kategoriler</label>
                        <Field
                            name="category_id"
                            as="select"
                            rules="required"
                            class="form-select"
                            v-model="formData.category_id"
                        >
                            <option disabled selected>Kategori Seçiniz</option>
                            <option
                                v-for="category_list in categories"
                                v-bind:key="category_list.id"
                                :value="category_list.id"
                            >
                                {{ category_list.title }}
                            </option>
                        </Field>
                        <ErrorMessage name="category_id" class="text-danger" />
                    </div>
                    <div class="input-area">
                        <label for="">Fiyat</label>
                        <Field
                            name="price"
                            as="input"
                            rules="required"
                            type="text"
                            class="form-control"
                            v-model="formData.price"
                        />
                        <ErrorMessage name="price" class="text-danger" />
                    </div>
                    <div class="input-area">
                        <label for="">Özellikler</label>
                        <Field
                            name="features"
                            as="input"
                            rules="required"
                            type="text"
                            class="form-control"
                            v-model="formData.features"
                        />
                        <ErrorMessage name="features" class="text-danger" />
                    </div>
                    <div class="input-area">
                        <label for="">Renk</label>
                        <Field
                            name="color"
                            as="input"
                            rules="required"
                            type="color"
                            class="form-control"
                            v-model="formData.colors"
                        />
                        <ErrorMessage name="color" class="text-danger" />
                    </div>
                    <div class="input-area">
                        <label for="">Ürün Resmi</label>
                        <Field
                            name="image"
                            as="input"
                            rules="required"
                            type="file"
                            class="form-control"
                            @change="product_image"
                        />
                        <ErrorMessage name="image" class="text-danger" />
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
        const formData = {
            title: "",
            price: "",
            features: "",
            colors: "",
            category_id: "",
        };
        const categories = ref([]);
        const image_path = ref(null);
        const toast = useToast();
        const validationSchema = yup.object({
            title: yup.string().required("Ürün ismi girimiz."),
            category_id: yup
                .number()
                .typeError("Geçerli bir kategori seçiniz")
                .required("Kategori seçiniz.")
                .integer("Kategori kimliği tam sayı olmalıdır"),
            price: yup
                .number()
                .typeError("Geçerli bir sayı giriniz.")
                .required("Fiyat giriniz")
                .integer("Fiyat tam sayı olmalıdır"),
            features: yup.string().required("Özellik giriniz."),
            color: yup
                .string()
                .matches(/^#[0-9A-F]{6}$/i, "Geçerli bir renk giriniz")
                .required("Renk giriniz."),
            image: yup.string().required("Resim Yükleyiniz."),
        });

        const { handleSubmit, errors } = useForm({
            validationSchema,
            initialValues: formData,
        });

        const handleSubmitWithValidation = async () => {
            const isValid = await handleSubmit(product_add)();
            if (!isValid) {
                for (const key in errors.value) {
                    if (errors.value[key]) {
                        toast.error(errors.value[key]);
                    }
                }
            }
        };

        const product_image = (e) => {
            const file = e.target.files[0];
            if (file) {
                image_path.value = file;
            } else {
                image_path.value = null;
            }
        };
        const product_add = async () => {
            const form = new FormData();
            const query = `
                    mutation(
                        $title: String!,
                        $category_id: ID!,
                        $price: String!,
                        $features: String!,
                        $colors: String!,
                        $file: Upload!
                    ) {
                        AddProduct(
                        title: $title,
                        category_id: $category_id,
                        price: $price,
                        features: $features,
                        colors: $colors,
                        image_path: $file
                        ) {
                            id
                        }
                    }
                `;
            const operations = {
                query: query,
                variables: {
                    title: formData.title,
                    category_id: Number(formData.category_id),
                    price: formData.price,
                    features: formData.features,
                    colors: formData.colors,
                    file: null,
                },
            };
            form.append("operations", JSON.stringify(operations));
            const map = {
                0: ["variables.file"],
            };

            form.append("map", JSON.stringify(map));
            form.append("0", image_path.value);
            try {
                const response = await axios.post("/graphql", form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                if (response.data.errors && response.data.errors.length > 0) {
                    toast.error(response.data.errors[0].message);
                } else {
                    toast.success("Ürün Eklendi");
                }
            } catch (error) {
                toast.error(error);
            }
        };
        const get_category = async () => {
            const response = await axios.post("/graphql", {
                query: `
                        query {
                            categories {
                                id
                                title
                            }
                        }
                    `,
            });
            categories.value = response.data.data.categories;
        };
        onMounted(() => {
            get_category();
        });
        return {
            formData,
            handleSubmit: handleSubmitWithValidation,
            categories,
            product_image,
        };
    },
};
</script>
<style></style>
