<template>
    <header>
        <div class="container">
            <div class="header-area">
                <div class="logo">
                    <router-link to="/">
                        <img :src="logoUrl" alt="Açıklama" />
                    </router-link>
                </div>
                <div class="user-account">
                    <ul>
                        <li>
                            <button class="basket-btn">
                                <font-awesome-icon :icon="['fas', 'bag-shopping']" />
                                cart 0
                            </button>
                        </li>
                        <li>
                            <router-link to="/hesabim">
                                <font-awesome-icon :icon="['far', 'user']" />
                                My account
                            </router-link>
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
