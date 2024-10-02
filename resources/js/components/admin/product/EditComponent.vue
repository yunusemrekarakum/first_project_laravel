<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Ürün Ekle</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="product_edit">
                    <div class="input-area">
                        <label for="">Ürün İsmi</label>
                        <input type="text" class="form-control" v-model="formData.title">
                    </div>
                    <div class="input-area">
                        <label for="">Kategoriler</label>
                        <select class="form-select" v-model="formData.category.id">
                            <option
                                v-for="category_list in categories"
                                :value="category_list.id"
                                :selected="category_list == formData.category.id"
                            >
                                {{ category_list.title }}
                            </option>
                        </select>
                    </div>
                    <div class="input-area">
                        <label for="">Fiyat</label>
                        <input type="text" class="form-control" v-model="formData.price">
                    </div>
                    <div class="input-area">
                        <label for="">Özellikler</label>
                        <input type="text" class="form-control" v-model="formData.features">
                    </div>
                    <div class="input-area">
                        <label for="">Renk</label>
                        <input type="color" class="form-control" v-model="formData.colors">
                    </div>
                    <div class="row">
                        <div class="col-9">
                            <div class="input-area">
                                <label for="">Ürün Resmi</label>
                                <input type="file" class="form-control" @change="product_image">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="img-area">
                                <img :src="formData.image_path" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-outline-success">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    name: "AddComponent",
    data() {
        return {
            categories: [],
            formData: {
                title: null,
                price: null,
                features: null,
                colors: null,
                image_path: null,
                category_id: null,
                category: {
                    id: null,
                    title: null
                }
            }
        };
    },
    methods: {
        async get_product() {
            const productId = this.$route.params.id;
            const response = await axios.post('/graphql', {
                query: `
                    query {
                        EditListProduct(id:${productId}) {
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
                `
            })
            this.formData = response.data.data.EditListProduct;
            if (this.formData.category) {
                this.formData.category_id = this.formData.category.id;
            }
        },
        async get_category() {
            const response = await axios.post("/graphql", {
                query: `
                        query {
                            categories {
                                id
                                title
                            }
                        }
                    `
            })
            this.categories = response.data.data.categories
        },
        product_image(e) {
            const file = e.target.files[0]
            if (file) {
                this.image_path = file;
            } else {
                this.image_path = null;
            }
        },
        async product_edit() {
            const productId = this.$route.params.id;
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
                    id: productId,
                    title: this.formData.title,
                    category_id: this.formData.category.id,
                    price: this.formData.price,
                    features: this.formData.features,
                    colors: this.formData.colors,
                    file: null
                }
            };
            const formData = new FormData;
            formData.append('operations', JSON.stringify(operations));
            const map = {'0': ['variables.file']};
            formData.append('map', JSON.stringify(map));
            if (this.image_path != null) {
                formData.append('0', this.image_path);
            }
            try {
                const response = await axios.post('/graphql', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                console.log('Product added successfully:', response.data);
            } catch (error) {
                console.error('Error adding product:', error);
            }
        }
    },
    mounted() {
        this.get_product();
        this.get_category();
    }
}

</script>
<style>

</style>
