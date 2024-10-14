import {
    createRouter,
    createWebHistory,
    type RouteRecordRaw,
} from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useConfigStore } from "@/stores/config";
import NProgress from "nprogress";
import "nprogress/nprogress.css";

declare module "vue-router" {
    interface RouteMeta {
        pageTitle?: string;
        permission?: string;
    }
}

const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        redirect: "/home",
    },
    {
        path: "/home",
        name: "home",
        component: () => import("@/pages/HomeView.vue"),
    },
    {
        path: "/tiket",
        component: () => import("@/pages/Foods.vue"),
    },
    {
        path: "/tiket/:id",
        component: () => import("@/pages/FoodDetail.vue"),
    },
    {
        path: '/payment/:orderId',
        name: 'paymentDetail',
        component: () => import('@/pages/PaymentDetail.vue'),
        props: true,
    },
    {
        path: "/tiket/:id/orders",
        component: () => import("@/pages/Orders.vue"),
    },
    {
        path: "/waiting-room",
        component: () => import("@/pages/WaitingRoom.vue"),
    },
    {
        path: "/dashboard",
        component: () => import("@/layouts/default-layout/DefaultLayout.vue"),
        meta: {
            middleware: "auth",
        },
        children: [
            {
                path: "/dashboard",
                name: "dashboard",
                component: () => import("@/pages/dashboard/Index.vue"),
                meta: {
                    pageTitle: "Dashboard",
                    breadcrumbs: ["Dashboard"],
                    middleware: "admin",
                },
            },
            {
                path: "/dashboard/profile",
                name: "dashboard.profile",
                component: () => import("@/pages/dashboard/profile/Index.vue"),
                meta: {
                    pageTitle: "Profile",
                    breadcrumbs: ["Profile"],
                },
            },
            {
                path: "/dashboard/setting",
                name: "dashboard.setting",
                component: () => import("@/pages/dashboard/setting/Index.vue"),
                meta: {
                    pageTitle: "Website Setting",
                    breadcrumbs: ["Website", "Setting"],
                },
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
                path: "/dashboard/orders",
                name: "dashboard.orders",
                component: () => import("@/pages/dashboard/orders/Index.vue"),
                meta: {
                    pageTitle: "Website Orders",
                    breadcrumbs: ["Website", "Orders"],
                },
            },
            {
                path: "/dashboard/orders/show",
                name: "dashboard.orders.show",
                component: () => import("@/pages/dashboard/orders/Show.vue"),
                meta: {
                    pageTitle: "Website Orders",
                    breadcrumbs: ["Website", "Orders"],
                },
            },

            // MASTER
            {
                path: "/dashboard/master/users/roles",
                name: "dashboard.master.users.roles",
                component: () =>
                    import("@/pages/dashboard/master/users/roles/Index.vue"),
                meta: {
                    pageTitle: "User Roles",
                    breadcrumbs: ["Master", "Users", "Roles"],
                },
            },
            {
                path: "/dashboard/master/users",
                name: "dashboard.master.users",
                component: () =>
                    import("@/pages/dashboard/master/users/Index.vue"),
                meta: {
                    pageTitle: "Users",
                    breadcrumbs: ["Master", "Users"],
                },
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"),
        children: [
            {
                path: "/sign-in",
                name: "sign-in",
                component: () => import("@/pages/auth/sign-in/Index.vue"),
                meta: {
                    pageTitle: "Masuk",
                    middleware: "guest",
                },
                beforeEnter: (to, from, next) => {
                    const authStore = useAuthStore();
                    if (authStore.isAuthenticated) {
                        // Jika pengguna sudah login sebagai admin atau user
                        if (authStore.user.role?.name === 'admin') {
                            next({ name: 'dashboard' });
                        } else {
                            next({ name: 'home' }); // Arahkan ke home untuk user
                        }
                    } else {
                        next();
                    }
                },
            },
            {
                path: "/sign-up",
                name: "sign-up",
                component: () => import("@/pages/auth/sign-up/Index.vue"),
                meta: {
                    pageTitle: "Daftar",
                    middleware: "guest",
                },
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/SystemLayout.vue"),
        children: [
            {
                // the 404 route, when none of the above matches
                path: "/404",
                name: "404",
                component: () => import("@/pages/errors/Error404.vue"),
                meta: {
                    pageTitle: "Error 404",
                },
            },
            {
                path: "/500",
                name: "500",
                component: () => import("@/pages/errors/Error500.vue"),
                meta: {
                    pageTitle: "Error 500",
                },
            },
        ],
    },
    {
        path: "/:pathMatch(.)",
        redirect: "/404",
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to) {
        // If the route has a hash, scroll to the section with the specified ID; otherwise, scroll to the top of the page.
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

router.beforeEach(async (to, from, next) => {
    if (to.name) {
        // Start the route progress bar.
        NProgress.start();
    }

    const authStore = useAuthStore();
    const configStore = useConfigStore();

    // current page view title
    if (to.meta.pageTitle) {
        document.title = `${to.meta.pageTitle} - ${import.meta.env.VITE_APP_NAME}`;
    } else {
        document.title = import.meta.env.VITE_APP_NAME as string;
    }

    // reset config to initial state
    configStore.resetLayoutConfig();

    // verify auth token before each page change
    if (!authStore.isAuthenticated) await authStore.verifyAuth();



    // Handle middleware
    if (to.meta.middleware) {
        if (to.meta.middleware === 'auth') {
            if (authStore.isAuthenticated) {
                next();
            } else {
                next({ name: 'sign-in' }); // Redirect to sign-in if not authenticated
            }
        } else if (to.meta.middleware === 'guest') {
            if (authStore.isAuthenticated) {
                next({ name: 'home' }); 
            } else {
                next(); // Allow access to guest routes
            }
        } else if (to.meta.middleware === 'admin') {
            if (authStore.isAuthenticated && authStore.user.role?.name === 'admin') {
                next();
            } else {
                next({ name: '404' }); // Redirect to 404 if not admin
            }
        }
    } else {
        next(); // No middleware, continue
    }
});


router.afterEach(() => {
    // Complete the animation of the route progress bar.
    NProgress.done();
});

export default router;