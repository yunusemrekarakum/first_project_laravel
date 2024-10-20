import './bootstrap';
import {
    createApp
} from 'vue';
import App from './components/App.vue';
import {
    library
} from '@fortawesome/fontawesome-svg-core';
import {
    FontAwesomeIcon
} from '@fortawesome/vue-fontawesome';
import {
    faBagShopping
} from '@fortawesome/free-solid-svg-icons';
import {
    faUser as farUser
} from '@fortawesome/free-regular-svg-icons';
import {
    faChevronDown
} from '@fortawesome/free-solid-svg-icons';
import HeaderComponent from "./components/HeaderComponent.vue";
import ProductComponent from "./components/ProductComponent.vue";
import LoginComponent from "./components/LoginComponent.vue";
import RegisterComponent from "./components/RegisterComponent.vue";
import AccountComponent from "./components/AccountComponent.vue";
//admin
import LoginAdmin from "./components/admin/LoginComponent.vue";
import HeaderAdmin from "./components/admin/HeaderComponent.vue";
import ListAdmin from "./components/admin/product/ListComponent.vue";
import AddAdmin from "./components/admin/product/AddComponent.vue";
import EditAdmin from "./components/admin/product/EditComponent.vue";
import AccountAdmin from "./components/admin/AccountComponent.vue";
import CategoryAdd from "./components/admin/category/AddComponent.vue";
import CategoryList from "./components/admin/category/ListComponent.vue";
import CategoryEdit from "./components/admin/category/EditComponent.vue";
import AdminList from "./components/admin/admin_create/List.vue";
import NotificationsSend from './components/admin/NotificationsSend.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import router from "./router/index.js";
import Vue3Session from "vue3-session";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import store from './store/index.js';
import {
    Form,
    Field,
    ErrorMessage,
    defineRule
} from 'vee-validate';

library.add(farUser, faBagShopping, faChevronDown);

const app = createApp(App);

var options = {
    persist: true,
    expiry: 2,
}
app.use(Vue3Session, options)
app.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true,
    position: "top-right",
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: true,
    hideProgressBar: true,
    closeButton: "button",
    icon: true,
    rtl: false
});
defineRule('required', value => !!value || 'Bu alan zorunludur.');
defineRule('min', (value, args) => {
    return value.length >= args[0] || `En az ${args[0]} karakter olmalı.`;
});

app.use(router)
app.use(store);

app.component('font-awesome-icon', FontAwesomeIcon);
app.component('header-component', HeaderComponent);
app.component('product-component', ProductComponent);
app.component('login-component', LoginComponent);
app.component('register-component', RegisterComponent);
app.component('account-component', AccountComponent);
//admin
app.component('login-admin', LoginAdmin);
app.component('header-admin', HeaderAdmin);
app.component('list-admin', ListAdmin);
app.component('add-admin', AddAdmin);
app.component('edit-admin', EditAdmin);
app.component('account-admin', AccountAdmin);
app.component('category-add', CategoryAdd);
app.component('category-list', CategoryList);
app.component('category-edit', CategoryEdit);
app.component('admin-list', AdminList);
app.component('notifications-send', NotificationsSend);

app.component('Form', Form);
app.component('Field', Field);
app.component('ErrorMessage', ErrorMessage);
app.mount('#app');
