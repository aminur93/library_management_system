import Index from "@/views/admin/user_management/user/Index.vue";
import Create from "@/views/admin/user_management/user/Create.vue";
import Edit from "@/views/admin/user_management/user/Edit.vue";

export default [

    {
        path: '/user',
        name: 'User',
        component: Index
    },

    {
        path: '/add-user',
        name: 'AddUser',
        component: Create
    },

    {
        path: '/edit-user/:id',
        name: 'EditUser',
        component: Edit
    }
];