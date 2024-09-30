<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Kategoriler</h1>
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
                        <th scope="col">Düzenle</th>
                        <th scope="col">Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in Category_list" :key="category.id">
                        <th scope="row">{{ category.title }}</th>
                        <td><router-link :to="`/admin/kategori-duzenle/${category.id}`" class="btn btn-success">Düzenle</router-link></td>
                        <td><button type="button" class="btn btn-danger" @click="category_delete(category.id)">Sil</button></td>
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
                Category_list: []
            };
        },
        methods: {
            async category_get() {
                const response_data = await axios.post("/graphql", {
                    query: `
                        query {
                            categories {
                                id
                                title
                            }
                        }
                    `
                })
                this.Category_list = response_data.data.data.categories
            },
            async category_delete (id) {
                try {
                    const response = await axios.post("/graphql", {
                        query: `
                            mutation {
                                DeleteCategories(id: ${id})
                            }
                        `
                    })
                    console.log(response.data.data.DeleteCategories);
                    this.Category_list = this.Category_list.filter(category => category.id !== id);
                } catch (error) {
                    console.error(error)
                }
            }
        },
        mounted() {
            this.category_get();
        },
    }

</script>
<style>

</style>
