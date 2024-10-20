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
                                <span class="mini-text">Ürün İsmi</span>
                                <p>{{ filterData.title ? filterData.title : 'All Product' }}</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter1">
                            <div class="filter-search-area">
                                <input v-model="filterData.title"
                                    @input="meilisearchfilter(filterData.title, 1)">
                            </div>
                        </div>
                    </div>
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter2')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Category</span>
                                <p>{{ filterData.category ? filterData.category : 'All Categories' }}</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter2">
                            <div class="filter-search-area">
                                <input v-model="filterData.category"
                                    @input="meilisearchfilter(filterData.category, 1)">
                            </div>
                        </div>
                    </div>
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter3')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Color</span>
                                <p>{{ filterData.color ? filterData.color : 'All Colors' }}</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter3">
                            <div class="filter-search-area">
                                <input v-model="filterData.color"
                                    @input="meilisearchfilter(filterData.color, 1)">
                            </div>
                        </div>
                    </div>
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter4')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Features</span>
                                <p>{{ filterData.features ? filterData.features : 'All Features' }}</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter4">
                            <div class="filter-search-area">
                                <input v-model="filterData.features"
                                    @input="meilisearchfilter(filterData.features, 1)">
                            </div>
                        </div>
                    </div>
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter5')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Price</span>
                                <p>
                                    From {{ filterData.min_price ? filterData.min_price : '0' }}₺ -
                                    {{ filterData.max_price ? filterData.max_price : '1000' }}₺
                                </p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter5">
                            <div class="filter-search-area">
                                <div class="d-flex align-items-center">
                                    <input v-model="filterData.min_price" placeholder="Min"
                                        @input="meilisearchfilter(filterData.min_price, currentPage)">
                                    <input v-model="filterData.max_price" placeholder="Max"
                                        @input="meilisearchfilter(filterData.max_price, currentPage)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-left-area">
                    <div class="filter-box">
                        <button class="filter-btn" @click="filterbtn('filter6')">
                            <div class="filter-btn-left">
                                <span class="mini-text">Sort</span>
                                <p>New In</p>
                            </div>
                            <div class="filter-icon">
                                <font-awesome-icon :icon="['fas', 'chevron-down']" />
                            </div>
                        </button>
                        <div class="filter-content" v-if="visibleFilters.filter6">
                            <div class="filter-search-area">
                                <ul class="w-100">
                                    <li><button @click="meilisearchfilter('desc', currentPage)"
                                            class="btn p-0 w-100">New</button>
                                    </li>
                                    <li><button @click="meilisearchfilter('asc', currentPage)"
                                            class="btn p-0 w-100">Old</button>
                                    </li>
                                </ul>
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
                <div class="col-md-3" v-for="value in products" v-bind:key="value.id">
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
                <button @click="meilisearchfilter(formData,currentPage - 1)" :disabled="currentPage === 1"
                    class="page-link">Önceki</button>
            </li>
            <li class="page-item" v-if="currentPage-1 >= 1">
                <button @click="meilisearchfilter(formData,currentPage-1)"
                    class="page-link">{{ currentPage-1 }}</button>
            </li>
            <li class="page-item active">
                <span @click="meilisearchfilter(formData,currentPage)" class="page-link active">{{ currentPage }}</span>
            </li>
            <li class="page-item" v-if="currentPage+1 <= lastPage">
                <button @click="meilisearchfilter(formData,currentPage+1)"
                    class="page-link">{{ currentPage+1 }}</button>
            </li>
            <li class="page-item" :class="{ disabled: lastPage === currentPage }">
                <button @click="meilisearchfilter(formData, currentPage + 1)" :disabled="lastPage === currentPage"
                    class="page-link">Sonraki</button>
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
                currentPage: isNaN(parseInt(window.location.pathname.split('/').pop())) ? 1 : parseInt(window.location
                    .pathname.split('/').pop()),
                perPage: 20,
                lastPage: null,
                hasMorePages: false,
                query: '',
                visibleFilters: {
                    filter1: false,
                    filter2: false,
                    filter3: false,
                    filter4: false,
                    filter5: false,
                    filter6: false
                },
                filterData: {
                    title: null,
                    category: null,
                    color: null,
                    features: null,
                    min_price: null,
                    max_price: null,
                    sort: null
                },
            };
        },
        methods: {
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
            async meilisearchfilter(value, page_val) {
                if (value == 'desc' || value == 'asc') {
                    this.filterData.sort = value;
                }
                const query = `
                    query {
                        SearchProducts(
                            page: ${page_val},
                            perPage: ${this.perPage},
                            title: "${this.filterData.title}"
                            category: "${this.filterData.category}",
                            color: "${this.filterData.color}",
                            features: "${this.filterData.features}",
                            min_price: ${this.filterData.min_price},
                            max_price: ${this.filterData.max_price},
                            sort: "${this.filterData.sort}"
                        ) {
                            results {
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
                            currentPage
                            lastPage
                            total
                        }
                    }
                `;
                const response = await axios.post('/graphql', {
                    query: query
                });
                const data = response.data.data.SearchProducts;
                this.products = data.results
                this.currentPage = data.currentPage;
                this.lastPage = data.lastPage;
                window.scrollTo({
                    top: 0,
                    behavior: 'auto'
                })
                history.replaceState(null, '', this.currentPage)
            }
        },
        mounted() {
            this.meilisearchfilter(this.filterData, this.currentPage);
        },
        name: 'ProductComponent'

    }

</script>
<style lang="">

</style>
