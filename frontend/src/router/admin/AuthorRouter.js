import Index from "@/views/admin/author/Index.vue";
import Create from "@/views/admin/author/Create.vue";
import Edit from "@/views/admin/author/Edit.vue";

export default [
    {
        path: '/author',
        name: 'Author',
        component: Index
    },

    {
        path: '/add-author',
        name: 'AddAuthor',
        component: Create
    },

    {
        path: '/edit-author/:id',
        name: 'EditAuthor',
        component: Edit
    }
];