<template>
    <div class="list-item">
        <div class="container">
            <div class="list-top">
                <div class="list-top-left">
                    <h1 class="title">Admin List</h1>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">İsim</th>
                        <th scope="col">Email</th>
                        <th scope="col">Adminlik</th>
                        <th scope="col">Yetkilendir</th>
                        <th scope="col">Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="value in users" v-bind:key="value.id">
                        <th scope="row">{{ value.id }}</th>
                        <th scope="row">{{ value.name }}</th>
                        <th scope="row">{{ value.email }}</th>
                        <td>
                            <button
                                @click="admin_add(value.id)"
                                class="btn" :class="value.roles[0].name == 'Admin' ? 'btn-danger' : 'btn-primary'"
                            >
                                {{ value.roles[0].name == 'Admin' ? 'Adminliğini al' : 'Admin Yap' }}
                            </button>
                        </td>
                        <td style="width: 45%">
                            <div class="permission-select-box">
                                <button
                                    @click="
                                        permission_update(
                                            value.id,
                                            permission.id
                                        )
                                    "
                                    v-for="permission in this.permission"
                                    :key="permission.id"
                                    :style="{
                                        backgroundColor: value.permissions.some(
                                            (p) => p.id === permission.id
                                        )
                                            ? '#198754'
                                            : 'red',
                                    }"
                                >
                                    {{ permission.name }}
                                </button>
                            </div>
                        </td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="delete_user(value.id)"
                            >
                                Sil
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page navigation example" class="mt-5 mb-5">
                <ul class="pagination justify-content-center">
                    <li
                        class="page-item"
                        :class="{ disabled: currentPage === 1 }"
                    >
                        <button
                            @click="fetchProducts(currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="page-link"
                        >
                            Önceki
                        </button>
                    </li>
                    <li class="page-item" v-if="currentPage + 1 <= lastPage">
                        <button
                            @click="fetchProducts(currentPage + 1)"
                            class="page-link"
                        >
                            {{ currentPage + 1 }}
                        </button>
                    </li>
                    <li class="page-item" v-else>
                        <button
                            @click="fetchProducts(currentPage - 1)"
                            class="page-link"
                        >
                            {{ currentPage - 1 }}
                        </button>
                    </li>
                    <li class="page-item" v-if="currentPage + 2 < lastPage">
                        <button
                            @click="fetchProducts(currentPage + 2)"
                            class="page-link"
                        >
                            {{ currentPage + 2 }}
                        </button>
                    </li>
                    <li class="page-item" v-if="currentPage + 3 < lastPage">
                        <button
                            @click="fetchProducts(currentPage + 3)"
                            class="page-link"
                        >
                            {{ currentPage + 3 }}
                        </button>
                    </li>
                    <li class="page-item" :class="{ disabled: !hasMorePages }">
                        <button
                            @click="fetchProducts(currentPage + 1)"
                            :disabled="!hasMorePages"
                            class="page-link"
                        >
                            Sonraki
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import { useToast } from "vue-toastification";
import { Field, ErrorMessage, useForm } from "vee-validate";
import * as yup from "yup";

export default {
    name: "ListComponent",
    data() {
        return {
            users: [],
            currentPage: 1,
            perPage: 20,
            hasMorePages: false,
            lastPage: null,
            query: "",
            toast: useToast(),
            permission: null,
            toast: useToast(),
        };
    },
    methods: {
        async get_user() {
            const query = `
                query {
                    allUser{
                        id
                        name
                        email
                        permissions {
                            id
                            name
                        }
                        roles {
                            name
                        }
                    }
                    permissionget {
                        id
                        name
                    }
                }
            `;
            const response = await axios.post("graphql", {
                query: query,
            });
            
            this.users = response.data.data.allUser;
            this.permission = response.data.data.permissionget;
        },
        async admin_add(id) {
            try {
                const query = `
                    query {
                        Admin_authority(id:${id}){
                            success
                            message
                        }
                    }`;
                const response = await axios.post("graphql", {
                    query: query,
                });
                if (response.data.data.Admin_authority.success) {
                    this.toast.success(response.data.data.Admin_authority.message);
                    await this.get_user();
                } else {
                    this.toast.error(response.data.errors[0].message);
                }
            } catch (errors) {
                this.toast.error(errors);
            }
        },
        async delete_user(id) {
            try {
                const query = `
                    mutation {
                        Delete_user(id:${id})
                    }
                `;
                const response = await axios.post("graphql", {
                    query: query,
                });
                this.toast.success("Silme İşlemi Başarılı");
                this.get_user();
            } catch (error) {
                this.toast.error(error);
            }
        },
        async permission_update(user_id, permission_id) {
            const query = `
                mutation {
                    permission_add (user_id: ${user_id}, permission_id: ${permission_id}) {
                        id
                        name
                        email
                        permissions {
                            id
                            name
                        }
                    }
                }
            `;
            const response = await axios.post("graphql", {
                query: query,
            });
            const updatedPermissions =
                response.data.data.permission_add.permissions;
            const user = this.users.find((u) => u.id === user_id);
            if (user) {
                user.permissions = [...updatedPermissions];
            }
            if (response.data.errors && response.data.errors.length > 0) {
                this.toast.error(response.data.errors[0].message);
            } else {
                this.toast.success("İzinler Güncellendi");
            }
        },
    },
    mounted() {
        this.get_user();
    },
};
</script>
<style></style>
