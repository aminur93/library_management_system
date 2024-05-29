import Index from "@/views/admin/member/Index.vue";
import Create from "@/views/admin/member/Create.vue";
import Edit from "@/views/admin/member/Edit.vue";

export default [
    {
        path: '/member',
        name: 'Member',
        component: Index
    },

    {
        path: '/add-member',
        name: 'AddMember',
        component: Create
    },

    {
        path: '/edit-member/:id',
        name: 'EditMember',
        component: Edit
    }
];