<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Ürünler</h1>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">İsim</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Renk</th>
                        <th scope="col">Düzenle</th>
                        <th scope="col">Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="value in products" v-bind:key="value.id">
                        <th scope="row">{{ value.id }}</th>
                        <th scope="row">{{ value.title }}</th>
                        <td>{{ value.category.title }}</td>
                        <td>
                            <span
                                class="product-color"
                                :style="{ backgroundColor: value.colors }"
                            ></span>
                        </td>
                        <td>
                            <router-link
                                :to="`/admin/urun-duzenle/${value.id}`"
                                class="btn btn-success"
                                >Düzenle
                            </router-link>
                        </td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="product_delete(value.id)"
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
                            @click="fetchProducts(1)"
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
                            @click="fetchProducts(currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="page-link"
                        >
                            Önceki
                        </button>
                    </li>
                    <li class="page-item" v-if="currentPage - 1 != 0">
                        <button
                            @click="fetchProducts(currentPage - 1)"
                            class="page-link"
                        >
                            {{ currentPage - 1 }}
                        </button>
                    </li>
                    <li class="page-item active">
                        <button
                            @click="fetchProducts(currentPage)"
                            class="page-link active"
                        >
                            {{ currentPage }}
                        </button>
                    </li>
                    <li class="page-item" v-if="currentPage + 1 <= lastPage">
                        <button
                            @click="fetchProducts(currentPage + 1)"
                            class="page-link"
                        >
                            {{ currentPage + 1 }}
                        </button>
                    </li>
                    <li class="page-item" :class="{ disabled: !hasMorePages }">
                        <button
                            @click="fetchProducts(currentPage + 1)"
                            :disabled="!hasMorePages"
                            class="page-link"
                        >
                            Sonraki
                        </button>
                    </li>
                    <li class="page-item" v-if="hasMorePages">
                        <button
                            @click="fetchProducts(lastPage)"
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
            products: [],
            currentPage: 1,
            perPage: 20,
            hasMorePages: false,
            lastPage: null,
            query: "",
            toast: useToast(),
            currentPage: isNaN(
                parseInt(window.location.pathname.split("/").pop())
            )
                ? 1
                : parseInt(window.location.pathname.split("/").pop()),
        };
    },
    methods: {
        async fetchProducts(page) {
            const query = `
                query($page:Int!, $perPage: Int!) {
                    productPage(page: $page, perPage: $perPage) {
                        data {
                            id
                            title
                            image_path
                            price
                            features
                            colors
                            category {
                                title
                            }
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
            const data = response.data.data.productPage;

            this.products = data.data;
            this.currentPage = data.paginatorInfo.currentPage;
            this.hasMorePages = data.paginatorInfo.hasMorePages;
            this.lastPage = data.paginatorInfo.lastPage;

            window.scrollTo({
                top: 0,
                behavior: "auto",
            });
            const currentUrl = window.location.origin + "/admin/" + this.currentPage;
            history.replaceState(null, '', currentUrl); 
        },
        async product_delete(id) {
            try {
                const response = await axios.post("graphql", {
                    query: `
                            mutation {
                                DeleteProduct(id: ${id})
                            }
                        `,
                });
                this.products = this.products.filter(
                    (product) => product.id !== id
                );
                //this.Product_list = this.Product_list.filter(product => product.id !== id);
                if (response.data.errors && response.data.errors.length > 0) {
                    toast.error(response.data.errors[0].message);
                } else {
                    toast.success("Silme işlemi başarılı");
                }
            } catch (error) {
                toast.error(error);
            }
        },
    },
    mounted() {
        this.fetchProducts(this.currentPage);
    },
};
</script>
<style></style>
