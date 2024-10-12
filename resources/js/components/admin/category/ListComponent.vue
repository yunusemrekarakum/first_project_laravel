<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Kategoriler</h1>
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
                        <td>
                            <router-link
                                :to="`/admin/kategori-duzenle/${category.id}`"
                                class="btn btn-success"
                                >Düzenle</router-link
                            >
                        </td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="category_delete(category.id)"
                            >
                                Sil
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page navigation example" class="mt-5 mb-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item" v-if="currentPage != 1">
                        <button
                            @click="category_get(1)"
                            :disabled="currentPage === 1"
                            class="page-link"
                        >
                            İlk Sayfa
                        </button>
                    </li>
                    <li
                        class="page-item"
                        :class="{ disabled: currentPage === 1 }"
                    >
                        <button
                            @click="category_get(currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="page-link"
                        >
                            Önceki
                        </button>
                    </li>
                    <li class="page-item" v-if="currentPage - 1 != 0">
                        <button
                            @click="category_get(currentPage - 1)"
                            class="page-link"
                        >
                            {{ currentPage - 1 }}
                        </button>
                    </li>
                    <li class="page-item active">
                        <button
                            @click="category_get(currentPage)"
                            class="page-link active"
                        >
                            {{ currentPage }}
                        </button>
                    </li>
                    <li class="page-item" v-if="currentPage + 1 <= lastPage">
                        <button
                            @click="category_get(currentPage + 1)"
                            class="page-link"
                        >
                            {{ currentPage + 1 }}
                        </button>
                    </li>
                    <li class="page-item" :class="{ disabled: !hasMorePages }">
                        <button
                            @click="category_get(currentPage + 1)"
                            :disabled="!hasMorePages"
                            class="page-link"
                        >
                            Sonraki
                        </button>
                    </li>
                    <li class="page-item" v-if="hasMorePages">
                        <button
                            @click="category_get(lastPage)"
                            :disabled="!hasMorePages"
                            class="page-link"
                        >
                            Son Sayfa
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import { useToast } from "vue-toastification";

export default {
    name: "ListComponent",
    data() {
        return {
            Category_list: [],
            currentPage: isNaN(
                parseInt(window.location.pathname.split("/").pop())
            )
                ? 1
                : parseInt(window.location.pathname.split("/").pop()),
            currentPage: 1,
            perPage: 20,
            hasMorePages: false,
            lastPage: null,
            query: "",
            toast: useToast(),
        };
    },
    methods: {
        async category_get(page) {
            const query = `
                query($page:Int!, $perPage: Int!) {
                    categryPage(page: $page, perPage: $perPage) {
                        data {
                            id
                            title
                        }
                        paginatorInfo {
                            currentPage
                            lastPage
                            hasMorePages
                            total
                        }
                    }
                }
                `;
            const response = await axios.post("/graphql", {
                query,
                variables: {
                    page,
                    perPage: this.perPage,
                },
            });
            const data = response.data.data.categryPage;

            this.Category_list = data.data;
            this.currentPage = data.paginatorInfo.currentPage;
            this.hasMorePages = data.paginatorInfo.hasMorePages;
            this.lastPage = data.paginatorInfo.lastPage;
            window.scrollTo({
                top: 0,
                behavior: "auto",
            });
            const currentUrl =
                window.location.origin +
                "/admin/kategori-listele/" +
                this.currentPage;
            history.replaceState(null, "", currentUrl);
        },
        async category_delete(id) {
            try {
                const response = await axios.post("/graphql", {
                    query: `
                        mutation {
                            DeleteCategories(id: ${id})
                        }
                    `,
                });
                console.log(response.data.data.DeleteCategories);
                this.Category_list = this.Category_list.filter(
                    (category) => category.id !== id
                );
                if (response.data.errors && response.data.errors.length > 0) {
                    this.toast.error(response.data.errors[0].message);
                } else {
                    this.toast.success("Silme işlemi başarılı");
                }
            } catch (error) {
                this.toast.error(error);
            }
        },
    },
    mounted() {
        this.category_get(this.currentPage);
    },
};
</script>
<style></style>
