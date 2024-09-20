import './bootstrap';
import { createApp } from 'vue';
import 'font-awesome/css/font-awesome.css';
import HeaderComponent from "./components/HeaderComponent.vue";

const app = createApp({});

app.component('header-component', HeaderComponent);
app.mount('#app');
