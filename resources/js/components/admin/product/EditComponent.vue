<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Ürün Güncelle</h1>
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
                            v-model="formData.category.id"
                        >
                            <option
                                v-for="category_list in categories"
                                :value="category_list.id"
                                v-bind:key="category_list.id"
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
                    <div class="row">
                        <div class="col-9">
                            <div class="input-area">
                                <label for="">Ürün Resmi</label>
                                <input
                                    type="file"
                                    class="form-control"
                                    @change="product_image"
                                />
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="img-area">
                                <img :src="formData.image_path" alt="" />
                            </div>
                        </div>
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
    name: "AddComponent",
    setup() {
        const formData = ref({
            title: "",
            price: "",
            features: "",
            colors: "",
            image_path: "",
            category_id: "",
            category: {
                id: "",
                title: "",
            },
        });
        const router = useRouter();
        const productId = ref(router.currentRoute.value.params.id);
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
        });

        const { handleSubmit, errors } = useForm({
            validationSchema,
            initialValues: formData,
        });

        const handleSubmitWithValidation = async () => {
            const isValid = await handleSubmit(product_edit)();
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
        const product_edit = async () => {
            const query = `
                mutation(
                    $id: ID!
                    $title: String,
                    $category_id: ID,
                    $price: String,
                    $features: String,
                    $colors: String,
                    $file: Upload
                ) {
                    EditProduct(
                    id: $id,
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
                    id: productId.value,
                    title: formData.value.title,
                    category_id: formData.value.category.id,
                    price: formData.value.price,
                    features: formData.value.features,
                    colors: formData.value.colors,
                    file: null,
                },
            };
            const form = new FormData();
            form.append("operations", JSON.stringify(operations));
            const map = {
                0: ["variables.file"],
            };
            form.append("map", JSON.stringify(map));
            if (image_path.value != null) {
                form.append("0", image_path.value);
            }
            try {
                const response = await axios.post("/graphql", form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                if (response.data.errors && response.data.errors.length > 0) {
                    toast.error(response.data.errors[0].message);
                } else {
                    toast.success("Ürün Güncellendi");
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
        const get_product = async () => {
            const response = await axios.post("/graphql", {
                query: `
                query {
                    EditListProduct(id:${productId.value}) {
                        id
                        title
                        price
                        features
                        image_path
                        colors
                        category {
                           id
                           title
                        }
                    }
                }
            `,
            });
            formData.value = response.data.data.EditListProduct;

            if (formData.value.category) {
                formData.value.category_id = formData.value.category.id;
            }
        };
        onMounted(() => {
            get_category();
            get_product();
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
