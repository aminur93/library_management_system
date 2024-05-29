import Index from "@/views/admin/book/Index.vue";
import Create from "@/views/admin/book/Create.vue";
import Edit from "@/views/admin/book/Edit.vue";

export default [
    {
        path: '/book',
        name: 'Book',
        component: Index
    },

    {
        path: '/add-book',
        name: 'AddBook',
        component: Create
    },

    {
        path: '/edit-book/:id',
        name: 'EditBook',
        component: Edit
    }
];