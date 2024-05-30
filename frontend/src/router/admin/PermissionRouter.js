import Index from "@/views/admin/user_management/permission/Index.vue";
import Create from "@/views/admin/user_management/permission/Create.vue";
import Edit from "@/views/admin/user_management/permission/Edit.vue";

export default [

    {
        path: '/permission',
        name: 'Permission',
        component: Index
    },

    {
        path: '/add-permission',
        name: 'AddPermission',
        component: Create
    },

    {
        path: '/edit-permission/:id',
        name: 'EditPermission',
        component: Edit
    }
];