/* -------------------------------------------------------------------------- */
/*                                states Define                               */
/* -------------------------------------------------------------------------- */
import {http} from "@/service/HttpService";

const state = {
    authors: [],
    author: {},
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
    GET_ALL_AUTHOR: (state, data) => {
        state.authors = data;
    },

    STORE_AUTHOR: (state, data) => {
        if (state.authors.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }else {
            state.success_message = '';
        }
    },

    GET_SINGLE_AUTHOR: (state, data) => {
        state.author = data;
    },

    UPDATE_AUTHOR: (state, data) => {
        if (state.authors.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }
    },

    DELETE_AUTHOR: (state, {id, data}) => {
        if (id)
        {
            state.authors = state.authors.filter(item => {
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
    /*get all author start*/
    async GetAllAuthor({ commit }) {
        try {
            const result = await http().get("v1/admin/author", {
                params: {
                    pagination: false
                }
            });
            commit("GET_ALL_AUTHOR", result.data.data);
        } catch (err) {
            const errors = err.response.data.errors;
            const errorStatus = err.response.status;
            commit("SET_ERROR", { errors, errorStatus });
            throw err; // Re-throw the error to propagate it to the caller
        }
    },
    /*get all author end*/

    /*store author start*/
    StoreAuthor: ({commit}, data) => {
        return http()
            .post("v1/admin/author", data)
            .then((result) => {
                commit("STORE_AUTHOR", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*store author end*/

    /*get single author start*/
    GetSingleAuthor: ({commit}, id) => {
        return http()
            .get(`v1/admin/author/${id}`)
            .then((result) => {
                commit("GET_SINGLE_AUTHOR", result.data.data);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*get single author end*/

    /*update leave category start*/
    UpdateAuthor: ({commit}, {id, data}) => {
        return http()
            .put(`v1/admin/author/${id}`, data)
            .then((result) => {
                commit("UPDATE_AUTHOR", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*update author end*/

    /*destroy author start*/
    DeleteAuthor: ({commit}, id) => {
        return http()
            .delete(`v1/admin/author/${id}`)
            .then((result) => {
                commit("DELETE_AUTHOR", {id:id, data:result});
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*destroy author end*/
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