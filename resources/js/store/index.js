import { createStore } from "vuex";
import axios from "axios";
const session = JSON.parse(localStorage.getItem("session")) || {};
const store = createStore({
    state: {
        isAuthenticated: !!session.token,
        token: session.token || null,
        tokenExpiry: session.token_time ? new Date(session.token_time) : null,
        userRole: null,
        permissions: [],
    },
    mutations: {
        setPermissions(state, permissions) {
            state.permissions = permissions;
        },
        setAuthentication(state, { isAuthenticated, token }) {
            state.isAuthenticated = isAuthenticated;
            state.token = token;
        },
        setTokenExpiry(state, expiry) {
            state.tokenExpiry = expiry;
        },
        setUserRole(state, role) {
            state.userRole = role;
        },
    },
    actions: {
        async userLogin({ commit }, { email, password }) {
            const mutation = `
                mutation {
                    userLogin(email: "${email}", password: "${password}") {
                        token
                    }
                }
            `;
            try {
                const response = await axios.post("/graphql", {
                    query: mutation,
                });
                const token = response.data.data.userLogin.token;

                const expiryDuration = 7200000; // 7200000 ms = 2 saat
                const expiryTime = new Date(
                    new Date().getTime() + expiryDuration
                );
                const sessionData = {
                    token: token,
                    token_time: expiryTime.toString()
                }
                commit("setAuthentication", { isAuthenticated: true, token });
                commit("setTokenExpiry", expiryTime.toISOString());
                localStorage.setItem("session", JSON.stringify(sessionData));

                return token; // Başarılı döndür
            } catch (error) {
                throw new Error(
                    error.response?.data.errors[0]?.message ||
                        "Giriş başarısız."
                );
            }
        },
        async fetchUserRole({ commit, state }) {
            try {
                const userquery = `
                    query {
                        userRole {
                            role
                        }
                    }`;
                const response = await axios.post(
                    "/graphql",
                    {
                        query: userquery,
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${state.token}`,
                            "Content-Type": "application/json",
                        },
                    }
                );
                const role = response.data.data.userRole.role; // Kullanıcı rolü burada gelir
                commit("setUserRole", role);
                return role;
            } catch (error) {
                throw new Error("Kullanıcı rolü alınamadı.");
            }
        },
        async fetchPermissions({ commit }) {
            const token = session.token;
            if (!token) return;
            const response = await axios.post(
                "/graphql",
                {
                    query: `
                            query {
                                user_permissions {
                                    name
                                }
                            }
                        `,
                },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            const permissionsString = response.data.data.user_permissions.name;
            const permissions = JSON.parse(permissionsString);
            
            commit("setPermissions", permissions);
        },
    },
    getters: {
        isAuthenticated: (state) => {
            return !!state.token;
        },
        userRole: (state) => {
            return state.userRole;
        },
        hasPermission: (state) => (permission) => {
            const permissionsArray = [...state.permissions];
            return state.permissions.includes(permission);
        },
    },
});

export default store;
