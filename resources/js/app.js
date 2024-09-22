import './bootstrap';
import { createApp } from 'vue';
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
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';


library.add(farUser, faBagShopping, faChevronDown);

const app = createApp({});

app.component('font-awesome-icon', FontAwesomeIcon);
app.component('header-component', HeaderComponent);
app.component('filter-component', FilterComponent);
app.component('product-component', ProductComponent);
app.component('login-component', LoginComponent);
app.component('register-component', RegisterComponent);
app.component('account-component', AccountComponent);
app.mount('#app');
