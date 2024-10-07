import {createRouter, createWebHistory} from 'vue-router';
import LoginAdmin from "../components/admin/LoginComponent.vue";

const HomePage = {
    template: `
        <div>
            <header-component></header-component>
            <product-component></product-component>
        </div>
    `,
};
const LoginPage = {
    template: `
        <div>
            <header-component></header-component>
            <login-component></login-component>
        </div>
    `,
}
const RegisterPage = {
    template: `
        <div>
            <header-component></header-component>
            <register-component></register-component>
        </div>
    `,
}
const AccountPage = {
    template: `
        <div>
            <header-component></header-component>
            <account-component></account-component>
        </div>
    `,
}
//admin
const AdminPage = {
    template: `
        <div>
            <header-admin></header-admin>
            <list-admin></list-admin>
        </div>
    `,
};
const ProductAdd = {
    template: `
        <div>
            <header-admin></header-admin>
            <add-admin></add-admin>
        </div>
    `,
}
const ProductEdit = {
    template: `
        <div>
            <header-admin></header-admin>
            <edit-admin></edit-admin>
        </div>
    `,
}
const AdminAccount = {
    template: `
        <div>
            <header-admin></header-admin>
            <account-admin></account-admin>
        </div>
    `,
}
const CategoryAdd = {
    template: `
        <div>
            <header-admin></header-admin>
            <category-add></category-add>
        </div>
    `,
}
const CategoryList = {
    template: `
        <div>
            <header-admin></header-admin>
            <category-list></category-list>
        </div>
    `,
}
const CategoryEdit = {
    template: `
        <div>
            <header-admin></header-admin>
            <category-edit></category-edit>
        </div>
    `,
}
const routes = [
    {
        path: '/',
        name: 'Home',
        component: HomePage,
    },
    {
        path: '/:page',
        name: 'Page',
        component: HomePage,
        props: true,
    },
    {
        path: '/giris-yap',
        name: 'Login',
        component: LoginPage,
        meta: {requiresAuth: false}
    },
    {
        path: '/kayit-ol',
        name: 'Register',
        component: RegisterPage,
    },
    {
        path: '/hesabim',
        name: 'Account',
        component: AccountPage,
    },
    {
        path: '/admin-giris',
        name: 'AdminLogin',
        component: LoginAdmin,
        meta: {requiresAuth: false}
    },
    {
        path: '/admin',
        name: 'Admin',
        component: AdminPage,
        meta: {requiresAuth: true}
    },
    {
        path: '/admin/urun-ekle',
        name: 'ProductAdd',
        component: ProductAdd,
        meta: {requiresAuth: true}
    },
    {
        path: '/admin/urun-duzenle/:id',
        name: 'ProductEdit',
        component: ProductEdit,
        meta: {requiresAuth: true}
    },
    {
        path: '/admin/hesabim',
        name: 'AdminAccount',
        component: AdminAccount,
        meta: {requiresAuth: true}
    },
    {
        path: '/admin/kategori-listele',
        name: 'CategoryList',
        component: CategoryList,
        meta: {requiresAuth: true}
    },
    {
        path: '/admin/kategori-ekle',
        name: 'CategoryAdd',
        component: CategoryAdd,
        meta: {requiresAuth: true}
    },
    {
        path: '/admin/kategori-duzenle/:id',
        name: 'CategoryEdit',
        component: CategoryEdit,
        meta: {requiresAuth: true}
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
});



router.beforeEach(async (to, from, next) => {
    const sessionData = localStorage.getItem('vue-session-key');
    if (sessionData) {
        const tokenExpiry = JSON.parse(localStorage.getItem('vue-session-key')).token_expiry;
        if (tokenExpiry) {
            const expiryDate = new Date(tokenExpiry);
            if (new Date(new Date().getTime()) > expiryDate) {
                localStorage.removeItem('vue-session-key');
            }
        }
    }
    const isAuthenticated = sessionData ? !!JSON.parse(sessionData).token : false;
    if (to.name == 'AdminLogin') {
        if (isAuthenticated) {
            next({name: "Admin"})
        } else {
            next()
        }
    } else {
        if (to.meta.requiresAuth) {
            if (!isAuthenticated) {
                next({name: "AdminLogin"})
            } else {
                next()
            }
        } else {
            next();
        }
    }
});

export default router;
