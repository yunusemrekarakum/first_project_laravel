
<template>
    <div class="product-view">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="title-area">
                        <h1 class="title">Get Inspired</h1>
                        <p>
                            Browsing for your next long-haul trip, everyday journey, or just fancy a look at what's new?
                            From
                            community favourites to about-to-sell-out items, see them all here.s
                        </p>
                    </div>
                </div>
            </div>
            <div class="filter-area">
                <div class="filter-left-area">
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter1')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Category</span>
                                <p>All Categories</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter1">
                            <div class="filter-search-area">
                                <input v-model="filterData.category" 
                                @input="meilisearchfilter('category', filterData.category)">
                            </div>
                        </div>
                    </div>
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter2')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Color</span>
                                <p>All Colors</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter2">
                            <div class="filter-search-area">
                                <input v-model="filterData.color"
                                @input="meilisearchfilter('color', filterData.color)"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter3')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Features</span>
                                <p>All Features</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter3">
                            <div class="filter-search-area">
                                <input v-model="filterData.features"
                                @input="meilisearchfilter('features', filterData.features)"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter4')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Price</span>
                                <p>From €0 - €1000</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter4">
                            <div class="filter-search-area">
                                <input v-model="filterData.price"
                                @input="meilisearchfilter('price', filterData.price)"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-left-area">
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter5')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Sort</span>
                                <p>New In</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter5">
                            <div class="filter-search-area">
                                <input type="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3" v-for="value in products">
                    <div class="product-item">
                        <div class="product-img">
                            <img :src="value.image_path" alt="">
                            <div class="colors">
                                <span :style="`background-color: ${value.colors}`"></span>
                            </div>
                        </div>
                        <div class="product-info">
                            <h2 class="product-name">{{ value.title }}</h2>
                            <p>{{ value.category.title }}</p>
                            <span class="price">{{ value.price }} ₺</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav aria-label="Page navigation example" class="mt-5 mb-5">
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button @click="fetchProducts(currentPage - 1)" :disabled="currentPage === 1" class="page-link">Önceki</button>
            </li>
            <li class="page-item">
                <button @click="fetchProducts(currentPage+1)" class="page-link">{{ currentPage+1 }}</button>
            </li>
            <li class="page-item">
                <button @click="fetchProducts(currentPage+2)" class="page-link">{{ currentPage+2 }}</button>
            </li>
            <li class="page-item">
                <button @click="fetchProducts(currentPage+3)" class="page-link">{{ currentPage+3 }}</button>
            </li>
            <li class="page-item" :class="{ disabled: !hasMorePages }">
                <button @click="fetchProducts(currentPage + 1)" :disabled="!hasMorePages" class="page-link">Sonraki</button>
            </li>
        </ul>
    </nav>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            productimg: '../assets/img/product.jpg',
            products: [],
            currentPage: 1,
            perPage: 20,
            hasMorePages: false,
            query: '',
            visibleFilters: {
                filter1: false,
                filter2: false,
                filter3: false,
                filter4: false,
                filter5: false
            },
            filterData: {
                category: null,
                color: null,
                features: null,
                price: null,
            },
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
            try {
                const response = await axios.post('/graphql', {
                    query,
                    variables: {
                        page,
                        perPage: this.perPage,
                    },
                })
                const data = response.data.data.productPage;
                this.products = data.data;
                this.currentPage = data.paginatorInfo.currentPage;
                this.hasMorePages = data.paginatorInfo.hasMorePages;
                window.scrollTo({top: 0, behavior: 'auto'})
            } catch (error) {
                console.error("Arama sırasında bir hata oluştu:", error);
            }
        },
        filterbtn(filter) {
            for (const key in this.visibleFilters) {
                if (key == filter) {
                    if (this.visibleFilters[filter] != true) {
                        this.visibleFilters[filter] = true
                    } else {
                        this.visibleFilters[filter] = false
                    }
                }
                if (key != filter) {
                    this.visibleFilters[key] = false
                }
            }
        },
        async meilisearchfilter(type, value) {
            this.filterData[type] = value;

            // Filter parametresini oluştur
            const filterConditions = [];
            if (this.filterData.category) {
                filterConditions.push(`category = "${this.filterData.category}"`);
            }
            if (this.filterData.color) {
                filterConditions.push(`color = "${this.filterData.color}"`);
            }
            if (this.filterData.features) {
                filterConditions.push(`features = "${this.filterData.features}"`);
            }
            if (this.filterData.price) {
                filterConditions.push(`price <= ${this.filterData.price}`);
            }
            
            
            const filters = filterConditions.join(' AND ');
            
            try {
                // Meilisearch API'sine istek gönder
                const response = await axios.get('http://localhost:7700/indexes/products_index/search', {
                    params: {
                        filter: filters
                    }
                });
                console.log(response);
                
                this.products = response.data.hits;
                
            } catch (error) {
                console.error('Error fetching products:', error);
            }
            
        }
    },
    mounted() {
        this.fetchProducts(this.currentPage);
    },
    name: 'ProductComponent'

}

</script>
<style lang="">

</style>
