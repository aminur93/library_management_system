import PageNotFound from "@/components/admin/404.vue";
export default [
    {path: '/page-not-found', name: 'PageNotFound', component: PageNotFound},
    { path: '/:catchAll(.*)', redirect: '/page-not-found' },
];