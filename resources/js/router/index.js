import { createRouter, createWebHistory } from "vue-router";
import LoginAdmin from "../components/admin/LoginComponent.vue";
import store from "../store";
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
};
const RegisterPage = {
    template: `
        <div>
            <header-component></header-component>
            <register-component></register-component>
        </div>
    `,
};
const AccountPage = {
    template: `
        <div>
            <header-component></header-component>
            <account-component></account-component>
        </div>
    `,
};
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
};
const ProductEdit = {
    template: `
        <div>
            <header-admin></header-admin>
            <edit-admin></edit-admin>
        </div>
    `,
};
const AdminAccount = {
    template: `
        <div>
            <header-admin></header-admin>
            <account-admin></account-admin>
        </div>
    `,
};
const CategoryAdd = {
    template: `
        <div>
            <header-admin></header-admin>
            <category-add></category-add>
        </div>
    `,
};
const CategoryList = {
    template: `
        <div>
            <header-admin></header-admin>
            <category-list></category-list>
        </div>
    `,
};
const CategoryEdit = {
    template: `
        <div>
            <header-admin></header-admin>
            <category-edit></category-edit>
        </div>
    `,
};
const AdminList = {
    template: `
        <div>
            <header-admin></header-admin>
            <admin-list></admin-list>
        </div>
    `,
};
const routes = [
    {
        path: "/",
        name: "Home",
        component: HomePage,
    },
    {
        path: "/:page",
        name: "Page",
        component: HomePage,
        props: true,
    },
    {
        path: "/giris-yap",
        name: "Login",
        component: LoginPage,
        meta: { requiresAuth: false },
    },
    {
        path: "/kayit-ol",
        name: "Register",
        component: RegisterPage,
        meta: { requiresAuth: false },
    },
    {
        path: "/hesabim",
        name: "Account",
        component: AccountPage,
        meta: { requiresAuth: true, userlogin: true, role: "User" },
    },
    {
        path: "/admin-giris",
        name: "AdminLogin",
        component: LoginAdmin,
        meta: { requiresAuth: false },
    },
    {
        path: "/admin",
        name: "Admin",
        component: AdminPage,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Products",
        },
    },
    {
        path: "/admin/:page",
        name: "AdminPage",
        component: AdminPage,
        props: true,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Products",
        },
    },
    {
        path: "/admin/urun-ekle",
        name: "ProductAdd",
        component: ProductAdd,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Products",
        },
    },
    {
        path: "/admin/urun-duzenle/:id",
        name: "ProductEdit",
        component: ProductEdit,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Products",
        },
    },
    {
        path: "/admin/hesabim",
        name: "AdminAccount",
        component: AdminAccount,
        meta: { requiresAuth: true, role: ["Admin", "Super Admin"] },
    },
    {
        path: "/admin/kategori-listele",
        name: "CategoryList",
        component: CategoryList,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Categories",
        },
    },
    {
        path: "/admin/kategori-listele/:page",
        name: "CategoryPage",
        component: CategoryList,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Categories",
        },
        props: true,
    },
    {
        path: "/admin/kategori-ekle",
        name: "CategoryAdd",
        component: CategoryAdd,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Categories",
        },
    },
    {
        path: "/admin/kategori-duzenle/:id",
        name: "CategoryEdit",
        component: CategoryEdit,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Categories",
        },
    },
    {
        path: "/admin-listele",
        name: "AdminList",
        component: AdminList,
        meta: {
            requiresAuth: true,
            role: ["Admin", "Super Admin"],
            requiresPermission: "Admins",
        },
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

const checkRole = (userRole, requiredRoles) => {
    if (!Array.isArray(requiredRoles)) {
        requiredRoles = [requiredRoles];
    }
    return requiredRoles.includes(userRole);
};
router.beforeEach(async (to, from, next) => {
    const sessionData = JSON.parse(localStorage.getItem("session"));
    if (sessionData) {
        const tokenExpiry = new Date(sessionData.token_time);
        if (tokenExpiry) {
            if (new Date() > tokenExpiry) {
                localStorage.removeItem("session");
                return next({ name: "AdminLogin" });
            }
        }
    }

    const isAuthenticated = store.getters.isAuthenticated;
    
    if (isAuthenticated) {
        try {
            await store.dispatch("fetchUserRole"); // Kullanıcı rolünü al
            await store.dispatch("fetchPermissions"); // kullanıcı izinlerini al
        } catch (error) {
            return next({ name: "AdminLogin" });
        }
    }
    const requiredPermission = to.meta.requiresPermission;

    const role = store.getters.userRole;
    if (role === "Super Admin") {
        return next();
    }

    if (to.meta.requiresAuth && isAuthenticated != true && to.meta.userlogin != true) {
        return next({ name: "AdminLogin" });
    }
    if (to.meta.userlogin && isAuthenticated != true) {
        return next({name: "Login"})
    }
    
    if (to.meta.requiresAuth && to.meta.role && !checkRole(role, to.meta.role)) {
        return next({ name: "Login" });
    }

    if (
        requiredPermission &&
        !store.getters.hasPermission(requiredPermission)
    ) {
        return next(from);
    }
    next(); // Tüm kontroller geçildiyse devam et
});

export default router;
