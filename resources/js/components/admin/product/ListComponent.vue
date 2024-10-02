<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Ürünler</h1>
                </div>
                <div class="list-top-right">
                    <div class="list-search">
                        <input type="text" class="form-control" placeholder="Ürün Adı">
                    </div>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">İsim</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Renk</th>
                        <th scope="col">Düzenle</th>
                        <th scope="col">Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="value in Product_list">
                        <th scope="row">{{ value.title }}</th>
                        <td>{{ value.category.title }}</td>
                        <td><span class="product-color" :style="{ backgroundColor: value.colors }"></span></td>
                        <td><router-link :to="`/admin/urun-duzenle/${value.id}`" class="btn btn-success">Düzenle</router-link></td>
                        <td><button type="button" class="btn btn-danger" @click="product_delete(value.id)">Sil</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    import axios from "axios";

    export default {
        name: "ListComponent",
        data() {
            return {
                Product_list: [],
            }
        },
        methods: {
            async products_get() {
                const response_data = await axios.post("/graphql", {
                    query: `
                        query {
                            products {
                                id
                                title
                                colors
                                category {
                                    title
                                }
                            }
                        }
                    `
                })
                this.Product_list = response_data.data.data.products
            },
            async product_delete(id) {
                try {
                    const response = await axios.post("graphql", {
                        query: `
                            mutation {
                                DeleteProduct(id: ${id})
                            }
                        `
                    });
                    this.Product_list = this.Product_list.filter(product => product.id !== id);
                } catch (error) {
                    console.log(error)
                }

            }
        },
        mounted() {
            this.products_get();
        }
    }

</script>
<style>

</style>
