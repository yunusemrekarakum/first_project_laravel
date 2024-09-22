<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Ürünler</h1>
                </div>
                <div class="list-top-right">
                    <div class="list-search">
                        <input type="text" class="form-control" placeholder="Ürün Adı">
                    </div>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">İsim</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Renk</th>
                        <th scope="col">Düzenle</th>
                        <th scope="col">Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Shibuya Totepack</th>
                        <td>Çanta</td>
                        <td><span class="product-color" style="background-color: red;"></span></td>
                        <td><a href="" class="btn btn-success">Düzenle</a></td>
                        <td><button type="button" class="btn btn-danger">Sil</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        name: "ListComponent"
    }

</script>
<style>

</style>
