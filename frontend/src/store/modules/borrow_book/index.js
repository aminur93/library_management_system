/* -------------------------------------------------------------------------- */
/*                                states Define                               */
/* -------------------------------------------------------------------------- */
import {http} from "@/service/HttpService";

const state = {
    borrowBooks: [],
    borrowBook: {},
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
    GET_ALL_BORROW_BOOK: (state, data) => {
        state.borrowBooks = data;
    },

    STORE_BORROW_BOOK: (state, data) => {
        if (state.borrowBooks.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }else {
            state.success_message = '';
        }
    },

    GET_SINGLE_BORROW_BOOK: (state, data) => {
        state.borrowBook = data;
    },

    UPDATE_BORROW_BOOK: (state, data) => {
        if (state.borrowBooks.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }
    },

    DELETE_BORROW_BOOK: (state, {id, data}) => {
        if (id)
        {
            state.borrowBooks = state.borrowBooks.filter(item => {
                return item.id !== id;
            })

            state.success_message = data.data.message;
            state.success_status = data.status;
        }
    },

    STATUS_CHANGE_BORROW_BOOK: (state, data) => {
        state.success_message = data.data.message;
        state.success_status = data.status;
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
    async GetAllBorrowBook({ commit }) {
        try {
            const result = await http().get("v1/admin/borrow-book", {
                params: {
                    pagination: false
                }
            });
            commit("GET_ALL_BORROW_BOOK", result.data.data);
        } catch (err) {
            const errors = err.response.data.errors;
            const errorStatus = err.response.status;
            commit("SET_ERROR", { errors, errorStatus });
            throw err; // Re-throw the error to propagate it to the caller
        }
    },
    /*get all book end*/

    /*store book start*/
    StoreBorrowBook: ({commit}, data) => {
        return http()
            .post("v1/admin/borrow-book", data)
            .then((result) => {
                commit("STORE_BORROW_BOOK", result);
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
    GetSingleBorrowBook: ({commit}, id) => {
        return http()
            .get(`v1/admin/borrow-book/${id}`)
            .then((result) => {
                commit("GET_SINGLE_BORROW_BOOK", result.data.data);
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
    UpdateBorrowBook: ({commit}, {id, data}) => {
        return http()
            .put(`v1/admin/borrow-book/${id}`, data)
            .then((result) => {
                commit("UPDATE_BORROW_BOOK", result);
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
    DeleteBorrowBook: ({commit}, id) => {
        return http()
            .delete(`v1/admin/borrow-book/${id}`)
            .then((result) => {
                commit("DELETE_BORROW_BOOK", {id:id, data:result});
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*destroy book end*/

    /*status change start*/
    statusChange: ({commit}, {id, data}) => {
        return http()
            .post(`v1/admin/borrow-book/status/${id}`, data)
            .then((result) => {
                commit("STATUS_CHANGE_BORROW_BOOK", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    }
    /*status change end*/
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