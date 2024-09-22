<template>
    <header>
        <div class="container">
            <div class="header-area">
                <div class="logo">
                    <a href="/">
                        <img :src="logoUrl" alt="Açıklama" />
                    </a>
                </div>
                <!--<div class="links">
                    <ul>
                        <li><a href="">Anasayfa</a></li>
                    </ul>
                </div> -->
                <div class="user-account">
                    <ul>
                        <li>
                            <button class="basket-btn">
                                <font-awesome-icon :icon="['fas', 'bag-shopping']" />
                                cart 0
                            </button>
                        </li>
                        <li>
                            <a href="">
                                <font-awesome-icon :icon="['far', 'user']" />
                                My account
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
    export default {
        data() {
            return {
                logoUrl: '../assets/img/logo.png',
            };
        },
        name: 'HeaderComponent'
    };

</script>

<style scoped>

</style>
