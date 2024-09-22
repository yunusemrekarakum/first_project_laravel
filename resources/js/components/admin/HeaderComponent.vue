<template>
    <header class="header-area">
        <div class="container-fluid">
            <div class="header-item">
                <div class="logo">
                    <a href="">
                        <img :src="logoUrl" alt="">
                    </a>
                </div>
                <div class="header-links">
                    <ul>
                        <li>
                            <a href="">Ürünler</a>
                            <ul class="sub-menu">
                                <li><a href="">Ekle</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="admin-account">
                    <div class="admin-account-content">
                        <div class="user-photo">
                            <a href="">
                                <img :src="userphoto" alt="">
                            </a>
                        </div>
                        <div class="user-info">
                            <a href="">
                                <span class="user-name">Yunus Emre Karakum</span>
                                <div class="icon">
                                </div>
                            </a>
                        </div>
                    </div>
                    <ul class="admin-account-other-link">
                        <li><a href="">Profil</a></li>
                        <li><a href="">Çıkış Yap</a></li>
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
                userphoto: '../assets/img/user-photo.png'
            };
        },
        name: "HeaderComponent"
    }

</script>
<style>

</style>
