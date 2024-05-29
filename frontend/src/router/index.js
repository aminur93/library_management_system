import {createWebHistory, createRouter} from "vue-router";
import AuthRouter from "@/router/auth/AuthRouter";
import AdminRouter from "@/router/admin/AdminRouter";
import commonRouter from "@/router/CommonRouter";

const routes = [
    ...AuthRouter,
    ...AdminRouter,
    ...commonRouter
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;