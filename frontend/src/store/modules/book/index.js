/* -------------------------------------------------------------------------- */
/*                                states Define                               */
/* -------------------------------------------------------------------------- */
import {http} from "@/service/HttpService";

const state = {
    books: [],
    book: {},
    success_message: "",
    errors: {},
    error_message: "",
    error_status: "",
    success_status: "",
}

/* -------------------------------------------------------------------------- */
/*                              Mutations Define                              */
/* -------------------------------------------------------------------------- */
const mutations = {
    GET_ALL_BOOK: (state, data) => {
        state.books = data;
    },

    STORE_BOOK: (state, data) => {
        if (state.books.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }else {
            state.success_message = '';
        }
    },

    GET_SINGLE_BOOK: (state, data) => {
        state.book = data;
    },

    UPDATE_BOOK: (state, data) => {
        if (state.books.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }
    },

    DELETE_BOOK: (state, {id, data}) => {
        if (id)
        {
            state.books = state.books.filter(item => {
                return item.id !== id;
            })

            state.success_message = data.data.message;
            state.success_status = data.status;
        }
    },

    SET_ERROR(state, { errors, errorStatus }) {
        state.errors = errors;
        state.error_status = errorStatus;
    }
}

/* -------------------------------------------------------------------------- */
/*                               Actions Define                               */
/* -------------------------------------------------------------------------- */
const actions = {
    /*get all book start*/
    async GetAllBook({ commit }) {
        try {
            const result = await http().get("v1/admin/book", {
                params: {
                    pagination: false
                }
            });
            commit("GET_ALL_BOOK", result.data.data);
        } catch (err) {
            const errors = err.response.data.errors;
            const errorStatus = err.response.status;
            commit("SET_ERROR", { errors, errorStatus });
            throw err; // Re-throw the error to propagate it to the caller
        }
    },
    /*get all book end*/

    /*store book start*/
    StoreBook: ({commit}, data) => {
        return http()
            .post("v1/admin/book", data)
            .then((result) => {
                commit("STORE_BOOK", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*store book end*/

    /*get single book start*/
    GetSingleBook: ({commit}, id) => {
        return http()
            .get(`v1/admin/book/${id}`)
            .then((result) => {
                commit("GET_SINGLE_BOOK", result.data.data);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*get single book end*/

    /*update book start*/
    UpdateBook: ({commit}, {id, data}) => {
        return http()
            .put(`v1/admin/book/${id}`, data)
            .then((result) => {
                commit("UPDATE_BOOK", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*update book end*/

    /*destroy book start*/
    DeleteBook: ({commit}, id) => {
        return http()
            .delete(`v1/admin/book/${id}`)
            .then((result) => {
                commit("DELETE_BOOK", {id:id, data:result});
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*destroy book end*/
}

/* -------------------------------------------------------------------------- */
/*                               Getters Define                               */
/* -------------------------------------------------------------------------- */
const getters = {}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}