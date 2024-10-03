
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
                            community favourites to about-to-sell-out items, see them all here.
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
                                <input v-model="query">
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
                                <input type="search">
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
                                <input type="search">
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
                                <input type="search">
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
                            <p>{{ value.features }}</p>
                            <span class="price">{{ value.price }} ₺</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            productimg: '../assets/img/product.jpg',
            products: [],
            query: '',
            visibleFilters: {
                filter1: false,
                filter2: false,
                filter3: false,
                filter4: false,
                filter5: false
            }
        };
    },
    watch: {
        query: 'search'
    },
    methods: {
        async get_product() {
            const response = await axios.post('graphql', {
                query: `
                        query {
                            products {
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
                        }
                    `
            })
            this.products = response.data.data.products
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
        async search() {
            if (this.query.length > 0) {
                const response = await axios.get("http://localhost:7700/indexes/products_index/search", {
                    params: {
                        q: this.query
                    }
                })
                this.products = response.data.hits
            }
        }
    },
    mounted() {
        this.get_product();
    },
    name: 'ProductComponent'

}

</script>
<style lang="">

</style>
