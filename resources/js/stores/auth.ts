import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface User {
    id: number;
    uuid: string;
    nama: string;
    email: string;
    phone: string;
    photo?: string;
    image?: string;
    password: string;
    datetime: string;
    permission: Array<string>;
    role?: {
        name: string;
        full_name: string;
    };
}

export const useAuthStore = defineStore("auth", () => {
    const error = ref<null | string>(null);
    const user = ref<User | null>(null);
    const isAuthenticated = ref(false);

    function setAuth(authUser: User, token = "") {
        isAuthenticated.value = true;
        user.value = authUser;
        error.value = null;

          // Simpan user ID ke localStorage
        localStorage.setItem('userId', authUser.id.toString()); // Simpan ID sebagai string

        if (token) {
            JwtService.saveToken(token);
        }
    }

    function purgeAuth() {
        isAuthenticated.value = false; // Menandakan pengguna sudah keluar
        user.value = {} as User; // Mereset user menjadi objek kosong
        error.value = null; // Menghapus error yang mungkin ada
    
        // Hapus user ID dari localStorage
        localStorage.removeItem('userId');
        
        JwtService.destroyToken(); // Menghapus token JWT
    }
    

    async function login(credentials: User) {
        return ApiService.post("auth/login", credentials)
            .then(({ data }) => {
                setAuth(data.user, data.token);
            })
            .catch(({ response }) => {
                error.value = response.data.message;
            });
    }

    async function logout() {
        if (JwtService.getToken()) {
            ApiService.setHeader();
            await ApiService.delete("auth/logout");
            purgeAuth();
        } else {
            purgeAuth();
        }
    }

    async function register(credentials: User) {
        return ApiService.post("auth/register", credentials)
            .then(({ data }) => {
                setAuth(data.user, data.token);
            })
            .catch(({ response }) => {
                error.value = response.data.message;
            });
    }

    async function forgotPassword(email: string) {
        return ApiService.post("auth/forgot_password", email)
            .then(() => {
                error.value = null;
            })
            .catch(({ response }) => {
                error.value = response.data.message;
            });
    }

    async function verifyAuth() {
        if (JwtService.getToken()) {
            ApiService.setHeader();
            await ApiService.get("auth/me")
                .then(({ data }) => {
                    setAuth(data.user);
                })
                .catch(({ response }) => {
                    error.value = response.data.message;
                    purgeAuth();
                });
        } else {
            purgeAuth();
        }
    }

    return {
        error,
        user,
        isAuthenticated,
        login,
        logout,
        register,
        forgotPassword,
        verifyAuth,
        setAuth,
        purgeAuth,
    };
});
