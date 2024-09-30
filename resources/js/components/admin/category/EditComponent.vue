<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Kategori Ekle</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="CategoryEdit">
                    <div class="input-area">
                        <label for="">Kategori İsmi</label>
                        <input type="text" class="form-control" v-model="Category_list.title">
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-outline-success">Güncelle</button>
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
            Category_list: []
        };
    },
    methods : {
        async CategoryEdit() {
            const title = this.Category_list.title
            const categoryId = this.$route.params.id;
            try {
                const response = await axios.post("/graphql", {
                    query: `
                        mutation {
                            EditCategories(id:"${categoryId}",title:"${title}") {
                                id
                                title
                            }
                        }
                    `
                });
                console.log(response.data.data.EditCategories)
            } catch (error) {
                console.log(error)
            }
        },
        async CategoryGet() {
            const categoryId = this.$route.params.id;
            const response = await axios.post("/graphql", {
                query: `
                    mutation {
                        EditListCategory(id: ${categoryId}) {
                            title
                        }
                    }
                `
            })
            this.Category_list = response.data.data.EditListCategory
        }
    },
    mounted() {
        this.CategoryGet();
    }
}

</script>
<style>

</style>
