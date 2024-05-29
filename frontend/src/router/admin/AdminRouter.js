import Master from "@/views/admin/Master.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import store from "@/store";
import AuthorRouter from "@/router/admin/AuthorRouter";
import memberRouter from "@/router/admin/MemberRouter";
import BookRouter from "@/router/admin/BookRouter";
import BorrowBookRouter from "@/router/admin/BorrowBookRouter";

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
            ...BorrowBookRouter
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