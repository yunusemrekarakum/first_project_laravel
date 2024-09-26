import './bootstrap';
import { createApp } from 'vue';
import App from './components/App.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faBagShopping } from '@fortawesome/free-solid-svg-icons';
import { faUser as farUser } from '@fortawesome/free-regular-svg-icons';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';
import HeaderComponent from "./components/HeaderComponent.vue";
import FilterComponent from "./components/FilterComponent.vue";
import ProductComponent from "./components/ProductComponent.vue";
import LoginComponent from "./components/LoginComponent.vue";
import RegisterComponent from "./components/RegisterComponent.vue";
import AccountComponent from "./components/AccountComponent.vue";
//admin
import LoginAdmin from "./components/admin/LoginComponent.vue";
import HeaderAdmin from "./components/admin/HeaderComponent.vue";
import ListAdmin from "./components/admin/product/ListComponent.vue";
import AddAdmin from "./components/admin/product/AddComponent.vue";
import AccountAdmin from "./components/admin/AccountComponent.vue";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import router from "./router/index.js";
import Vue3Session from "vue3-session";


library.add(farUser, faBagShopping, faChevronDown);

const app = createApp(App);

var options = {
    persist: true,
    expiry: 7200,
}
app.use(Vue3Session, options)
app.use(router)

app.component('font-awesome-icon', FontAwesomeIcon);
app.component('header-component', HeaderComponent);
app.component('filter-component', FilterComponent);
app.component('product-component', ProductComponent);
app.component('login-component', LoginComponent);
app.component('register-component', RegisterComponent);
app.component('account-component', AccountComponent);
//admin
app.component('login-admin', LoginAdmin);
app.component('header-admin', HeaderAdmin);
app.component('list-admin', ListAdmin);
app.component('add-admin', AddAdmin);
app.component('account-admin', AccountAdmin);
app.mount('#app');
