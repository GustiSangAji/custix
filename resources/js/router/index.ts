import {
    createRouter,
    createWebHistory,
    type RouteRecordRaw,
} from "vue-router";
import { defineAsyncComponent } from "vue";
import { useAuthStore } from "@/stores/auth"; // Store untuk autentikasi
import { useConfigStore } from "@/stores/config"; // Store untuk konfigurasi
import NProgress from "nprogress"; // Library untuk menampilkan progress bar
import "nprogress/nprogress.css"; // Gaya untuk progress bar

// Deklarasi module untuk menambahkan properti meta pada route
declare module "vue-router" {
    interface RouteMeta {
        pageTitle?: string; // Judul halaman
        permission?: string; // Izin akses
    }
}

// Mendefinisikan rute
const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        redirect: "/home", // Mengalihkan root ke halaman home
    },
    {
        path: "/home",
        name: "home",
        component: () => import("@/pages/HomeView.vue"), // Halaman utama
    },
    {
        path: "/tiket",
        component: () => import("@/pages/Foods.vue"), // Halaman untuk daftar tiket
    },
    {
        path: "/tiket/:id",
        name: "ticket-detail",
        component: () => import("@/pages/FoodDetail.vue"), // Halaman detail tiket
    },
    {
        path: '/payment/:orderId',
        name: 'paymentDetail',
        component: () => import('@/pages/PaymentDetail.vue'), // Halaman detail pembayaran
        props: true,
    },
    {
        path: "/waiting-room",
        name: 'waitingroom',
        component: () => import("@/pages/WaitingRoom.vue"),

    },
    {

        path: "/afterpayment/:orderId",
        name: "afterpayment", 
        component: () => import("@/pages/AfterPayment.vue"),
    },
    {
        path: "/informasi-pribadi",
        component: () => import("@/pages/setting/InformasiPribadi.vue"),
    },
    {
        path: '/verify',
        name: 'Verify',
        component: () => import("@/pages/Verify.vue"),
        props: (route) => ({ order_id: route.query.order_id }),
    },    
    {
        path: "/order",
        component: () => import("@/pages/Setting.vue"), // Pastikan ini layout yang benar
        children: [
            {
                path: "",
                name: "TiketSaya",
                component: () => import("@/components/tiket/TiketSaya.vue"), // Halaman Tiket Saya
            },
            {
                path: "orders/:id",
                name: 'OrderDetail',
                component: () => import("@/components/order/DetailOrder.vue"),
                props: true,  
            },
        ]
    },

    {
        path: "/dashboard",
        component: () => import("@/layouts/default-layout/DefaultLayout.vue"), // Layout untuk dashboard
        meta: {
            middleware: "auth", // Middleware untuk autentikasi
        },
        children: [
            {
                path: "",
                name: "dashboard",
                component: () => import("@/pages/dashboard/Index.vue"), // Halaman utama dashboard
                meta: {
                    pageTitle: "Dashboard", // Judul halaman dashboard
                    breadcrumbs: ["Dashboard"],
                    middleware: "admin", // Middleware untuk admin
                },
            },
            {
                path: "profile",
                name: "dashboard.profile",
                component: () => import("@/pages/dashboard/profile/Index.vue"), // Halaman profil
                meta: {
                    pageTitle: "Profile",
                    breadcrumbs: ["Profile"],
                },
            },
            {
                path: "setting",
                name: "dashboard.setting",
                component: () => import("@/pages/dashboard/setting/Index.vue"), // Halaman pengaturan
                meta: {
                    pageTitle: "Website Setting",
                    breadcrumbs: ["Website", "Setting"],
                },
            },
            {
                path: "laporan",
                name: "dashboard.laporan",
                component: () => import("@/pages/dashboard/laporan/Index.vue"), // Halaman laporan
                meta: {
                    pageTitle: "Website Laporan",
                    breadcrumbs: ["Website", "laporan"],
                },
            },
            {
                path: "/laporan/export/excel",
                name: "dashboard.laporan.export",
                component: defineAsyncComponent(() => {
                    window.open('http://192.168.61.123:8000/api/laporan/export/excel'); // Ganti dengan URL yang sesuai
                    return Promise.resolve({ render() { return null; } });
                }),
            },
            {
                path: "/event/tiket",
                name: "event.tiket",
                component: () => import("@/pages/dashboard/event/tiket/Index.vue"),
                meta: {
                    pageTitle: "Website Tiket",
                    breadcrumbs: ["Event", "Tiket"],
                },
            },
            {
                path: "/event/stockin",
                name: "event.stockin",
                component: () => import("@/pages/dashboard/event/stockin/Index.vue"),
                meta: {
                    pageTitle: "Penambahan Stock",
                    breadcrumbs: ["Event", "Stock"],
                },
            },
            {
                path: "orders/show",
                name: "dashboard.orders.show",
                component: () => import("@/pages/dashboard/orders/Show.vue"),
                meta: {
                    pageTitle: "Website Orders",
                    breadcrumbs: ["Website", "Orders"],
                },
            },

            // MASTER
            {
                path: "master/users/roles",
                name: "dashboard.master.users.roles",
                component: () => import("@/pages/dashboard/master/users/roles/Index.vue"),
                meta: {
                    pageTitle: "User Roles",
                    breadcrumbs: ["Master", "Users", "Roles"],
                },
            },
            {
                path: "master/users",
                name: "dashboard.master.users",
                component: () => import("@/pages/dashboard/master/users/Index.vue"),
                meta: {
                    pageTitle: "Users",
                    breadcrumbs: ["Master", "Users"],
                },
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"), // Layout untuk autentikasi
        children: [
            {
                path: "sign-in",
                name: "sign-in",
                component: () => import("@/pages/auth/sign-in/Index.vue"), // Halaman login
                meta: {
                    pageTitle: "Masuk",
                    middleware: "guest", // Middleware untuk tamu
                },
                beforeEnter: (to, from, next) => {
                    const authStore = useAuthStore();
                    if (authStore.isAuthenticated) {
                        // Jika pengguna sudah login sebagai admin atau user
                        if (authStore.user.role?.name === 'admin') {
                            next({ name: 'dashboard' }); // Arahkan ke dashboard
                        } else {
                            next({ name: 'home' }); // Arahkan ke home untuk user
                        }
                    } else {
                        next(); // Lanjutkan ke halaman login
                    }
                },
            },
            {
                path: "sign-up",
                name: "sign-up",
                component: () => import("@/pages/auth/sign-up/Index.vue"), // Halaman pendaftaran
                meta: {
                    pageTitle: "Daftar",
                    middleware: "guest", // Middleware untuk tamu
                },
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/SystemLayout.vue"), // Layout untuk sistem
        children: [
            {
                path: "/404",
                name: "404",
                component: () => import("@/pages/errors/Error404.vue"), // Halaman error 404
                meta: {
                    pageTitle: "Error 404",
                },
            },
            {
                path: "/500",
                name: "500",
                component: () => import("@/pages/errors/Error500.vue"), // Halaman error 500
                meta: {
                    pageTitle: "Error 500",
                },
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*", // Route catch-all
        redirect: "/404", // Redirect ke halaman 404
    },
];

// Membuat router
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to) {
        // Mengatur perilaku scroll ketika berpindah rute
        if (to.hash) {
            return {
                el: to.hash,
                top: 80,
                behavior: "smooth",
            };
        } else {
            return {
                top: 0,
                left: 0,
                behavior: "smooth",
            };
        }
    },
});

// Middleware sebelum setiap rute
router.beforeEach(async (to, from, next) => {
    if (to.name) {
        // Mulai progress bar jika ada nama rute
        NProgress.start();
    }

    const authStore = useAuthStore();
    const configStore = useConfigStore();

    // Mengatur judul halaman
    if (to.meta.pageTitle) {
        document.title = `${to.meta.pageTitle} - ${import.meta.env.VITE_APP_NAME}`;
    } else {
        document.title = import.meta.env.VITE_APP_NAME as string;
    }

    // Reset konfigurasi
    configStore.resetLayoutConfig();

    // Verifikasi token autentikasi sebelum berpindah rute
    if (!authStore.isAuthenticated) await authStore.verifyAuth();

    // Handle middleware
    if (to.meta.middleware) {
        if (to.meta.middleware === 'auth') {
            if (authStore.isAuthenticated) {
                next(); // Lanjutkan jika terautentikasi
            } else {
                next({ name: 'sign-in' }); // Redirect ke login jika tidak terautentikasi
            }
        } else if (to.meta.middleware === 'guest') {
            if (authStore.isAuthenticated) {
                next({ name: 'home' }); // Redirect ke home jika sudah terautentikasi
            } else {
                next(); // Lanjutkan untuk tamu
            }
        } else if (to.meta.middleware === 'admin') {
            if (authStore.isAuthenticated && authStore.user.role?.name === 'admin') {
                next(); // Lanjutkan jika admin
            } else {
                next({ name: '404' }); // Redirect ke 404 jika bukan admin
            }
        }
    } else {
        next(); // Tidak ada middleware, lanjutkan
    }
});

// Setelah berpindah rute
router.afterEach(() => {
    // Selesaikan animasi progress bar
    NProgress.done();
});

// Ekspor router
export default router;
