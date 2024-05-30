import Index from "@/views/admin/user_management/role/Index.vue";
import Create from "@/views/admin/user_management/role/Create.vue";
import Edit from "@/views/admin/user_management/role/Edit.vue";

export default [

    {
        path: '/role',
        name: 'Role',
        component: Index
    },

    {
        path: '/add-role',
        name: 'AddRole',
        component: Create
    },

    {
        path: '/edit-role/:id',
        name: 'EditRole',
        component: Edit
    }
];