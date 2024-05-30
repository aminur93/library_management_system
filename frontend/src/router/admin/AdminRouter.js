import Master from "@/views/admin/Master.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import store from "@/store";
import AuthorRouter from "@/router/admin/AuthorRouter";
import memberRouter from "@/router/admin/MemberRouter";
import BookRouter from "@/router/admin/BookRouter";
import BorrowBookRouter from "@/router/admin/BorrowBookRouter";
import PermissionRouter from "@/router/admin/PermissionRouter";
import RoleRouter from "@/router/admin/RoleRouter";
import UserRouter from "@/router/admin/UserRouter";

export default [
    {
        path: '/dashboard',
        component: Master,
        children: [
            {
                path: '',
                name: 'Dashboard',
                component: Dashboard,
            },

            ...AuthorRouter,
            ...memberRouter,
            ...BookRouter,
            ...BorrowBookRouter,
            ...PermissionRouter,
            ...RoleRouter,
            ...UserRouter
        ],
        beforeEnter(to, from, next){
            if (!store.getters['AuthToken'])
            {
                next({name: 'Login'});
            }else {
                next();
            }
        }
    }
];