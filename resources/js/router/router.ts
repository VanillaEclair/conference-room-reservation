// import {createRouter, createWebHistory} from "vue-router";

// const routes = [];

// export default createRouter(
//     {
//         history: createWebHistory(),
//         routes,});

// import { createRouter, createWebHistory, type RouteRecordRaw } from "vue-router";

// const routes: RouteRecordRaw[] = [];

// const router = createRouter({
//     history: createWebHistory(),
//     routes,
// });

// export default router;

import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";
import HomeView from "../views/HomeView.vue";
import LoginView from "../views/LoginView.vue";
import ReservationTableView from "../views/ReservationTableView.vue";
import CreateReservationView from "../views/CreateReservationView.vue";
import CreateUserView from "../views/CreateUserView.vue";
import CreateRoomView from "../views/CreateRoomView.vue";


const routes = [
    { path: "/", component: ReservationTableView, meta: { requiresAuth: true } },
    { path: "/login", component: LoginView, meta: { requiresGuest: true } },
    { path: "/create-reservation", component: CreateReservationView, meta: { requiresAuth: true } },
    { path: "/register", component: CreateUserView, meta: { requiresGuest: true }  },
    { path: "/create-room", component: CreateRoomView, meta: { requiresAuth: true }  },
    { path: "/reservation/:id/edit", component: CreateReservationView, meta: {requiresAuth: true}}
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();
    await auth.fetchUser();

    if (to.meta.requiresGuest && auth.isLoggedIn) {
        return "/";
    }

    if (to.meta.requiresAuth && !auth.isLoggedIn) {
        return "/login";
    }
});

export default router;
