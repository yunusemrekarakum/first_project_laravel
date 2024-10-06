<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Ürün Ekle</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="product_add">
                    <div class="input-area">
                        <label for="">Ürün İsmi</label>
                        <input type="text" class="form-control" v-model="formData.title">
                    </div>
                    <div class="input-area">
                        <label for="">Kategoriler</label>
                        <select class="form-select" v-model="formData.category_id">
                            <option disabled selected>Kategori Seçiniz</option>
                            <option v-for="category_list in categories" v-bind:key="category_list.id"
                                :value="category_list.id">
                                {{category_list.title}}
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
                    <div class="input-area">
                        <label for="">Ürün Resmi</label>
                        <input type="file" class="form-control" @change="product_image">
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
        name: "ProductAdd",
        data() {
            return {
                categories: [],
                image_path: null,
                formData: {
                    title: null,
                    price: null,
                    features: null,
                    colors: null,
                    category_id: null,
                }
            };
        },
        methods: {
            product_image(e) {
                const file = e.target.files[0]
                if (file) {
                    this.image_path = file;
                } else {
                    this.image_path = null;
                }
            },
            async product_add() {
                const formData = new FormData();
                if (!this.image_path) {
                    alert("Lütfen bir ürün resmi yükleyin.")
                    return;
                }
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
                        title: this.formData.title,
                        category_id: Number(this.formData.category_id),
                        price: this.formData.price,
                        features: this.formData.features,
                        colors: this.formData.colors,
                        file: null
                    }
                };
                formData.append('operations', JSON.stringify(operations));
                const map = {
                    '0': ['variables.file']
                };
                formData.append('map', JSON.stringify(map));
                formData.append('0', this.image_path);
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
            }
        },
        mounted() {
            this.get_category()
        }
    }

</script>
<style>

</style>
