import {createStore} from "vuex";
import createPersistedState from "vuex-persistedstate";

//root vuex import start
import state from "./state";
import * as getters from "./getters";
import * as mutations from "./mutations";
import * as actions from "./actions";
//root vuex import end

//module import start
import author from "@/store/modules/author";
import member from "@/store/modules/member";
import book from "@/store/modules/book";
import borrow_book from "@/store/modules/borrow_book";
import permission from "@/store/modules/user_management/permission";
import roles from "@/store/modules/user_management/role";
import users from "@/store/modules/user_management/user";
//module import end

const store = createStore({
    state,
    getters,
    mutations,
    actions,

    modules: {
        permission,
        roles,
        users,
        author,
        member,
        book,
        borrow_book
    },

    plugins: [
        createPersistedState({
            paths: ["token", "user", "permissions","role"]
        })
    ]
});

export default store