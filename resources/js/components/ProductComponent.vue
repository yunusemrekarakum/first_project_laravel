<template>
    <div class="product-area">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="product-item">
                        <div class="product-img">
                            <img :src="productimg" alt="">
                            <div class="colors">
                                <span></span>
                                +1
                            </div>
                        </div>
                        <div class="product-info">
                            <h2 class="product-name">Shibuya Totepack</h2>
                            <p>Recycled PET Rip Stop</p>
                            <span class="price">€140.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                productimg: '../assets/img/product.jpg'
            };
        },
        name: 'ProductComponent'
    }

</script>
<style lang="">

</style>
