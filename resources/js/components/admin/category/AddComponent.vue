<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Kategori Ekle</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="CategoryAdd">
                    <div class="input-area">
                        <label for="">Kategori İsmi</label>
                        <input type="text" class="form-control" v-model="category_title">
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
        methods : {
            async CategoryAdd() {
                const title = this.category_title
                try {
                    const response = await axios.post("/graphql", {
                        query: `
                        mutation {
                            AddCategories(title:"${title}") {
                                id
                                title
                            }
                        }
                    `
                    });
                    console.log(response.data.data.AddCategories)
                } catch (error) {
                    console.log(error)
                }
                // const response = await axios.post("/graphql", )
            }
        }
    }

</script>
<style>

</style>
