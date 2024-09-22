<template lang="">
    <div class="account-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="account-box">
                        <span class="account-name">Yunus Emre Karakum</span>
                        <ul class="account-list">
                            <li class="active"><a href="">Hesabım</a></li>
                            <li><a href="">Çıkış</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="account-box">
                        <form action="">
                            <span class="account-title">Hesap Bilgilerimi Düzenle</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Adınız Soyadınız</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">E-posta Adresi</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <span class="account-title">Şifre Değiştir</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Eski Şifre</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-input">
                                        <label for="">Yeni Şifre</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <span class="account-title">Profil Resmi</span>
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="form-input">
                                        <input type="file" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="profile-photo">
                                        <img src="">
                                    </div>
                                </div>
                            </div>
                            <div class="login-btn-area">
                                <button type="submit" class="site-btn w-100">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "AccountComponent"
    }

</script>
<style lang="">

</style>
