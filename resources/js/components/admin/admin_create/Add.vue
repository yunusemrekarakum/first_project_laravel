<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Admin Ekle</h1>
                </div>
            </div>
            <div class="admin-panel-form">
                <form @submit.prevent="">
                    <div class="input-area">
                        <label for="">Admin</label>
                        <input type="text" class="form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "axios";

    export default {
        name: "AdminCreate",
        data() {
        },
    }

</script>
<style>

</style>
