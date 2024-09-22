import './bootstrap';
import { createApp } from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core'; 
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faBagShopping } from '@fortawesome/free-solid-svg-icons';
import { faUser as farUser } from '@fortawesome/free-regular-svg-icons'; 
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';
import LoginComponent from "./components/admin/LoginComponent.vue";
import HeaderComponent from "./components/admin/HeaderComponent.vue";
import ListComponent from "./components/admin/product/ListComponent.vue";
import AddComponent from "./components/admin/product/AddComponent.vue";
import AccountComponent from "./components/admin/AccountComponent.vue";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';


library.add(farUser, faBagShopping, faChevronDown);

const admin = createApp({});

admin.component('font-awesome-icon', FontAwesomeIcon);
admin.component('login-component', LoginComponent);
admin.component('header-component', HeaderComponent);
admin.component('list-component', ListComponent);
admin.component('add-component', AddComponent);
admin.component('account-component', AccountComponent);
admin.mount('#admin');
