import Index from "@/views/admin/borrow_book/Index.vue";
import Create from "@/views/admin/borrow_book/Create.vue";
import Edit from "@/views/admin/borrow_book/Edit.vue";

export default [
    {
        path: '/borrow-book',
        name: 'BorrowBook',
        component: Index
    },

    {
        path: '/add-borrow-book',
        name: 'AddBorrowBook',
        component: Create
    },

    {
        path: '/edit-borrow-book/:id',
        name: 'EditBorrowBook',
        component: Edit
    }
];