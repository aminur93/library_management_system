/* -------------------------------------------------------------------------- */
/*                                states Define                               */
/* -------------------------------------------------------------------------- */

import {http} from "@/service/HttpService";

const state = {
    permissions: [],
    permission: {},
    pagination: false,
    success_message: "",
    errors: {},
    error_message: "",
    error_status: "",
    success_status: "",
};

/* -------------------------------------------------------------------------- */
/*                              Mutations Define                              */
/* -------------------------------------------------------------------------- */
const mutations = {
    GET_ALL_PERMISSION: (state, data) => {
        state.permissions = data;
    },

    STORE_PERMISSION: (state, data) => {
        if (state.permissions.push(data.data)) {
            state.success_message = data.data.message;
            state.success_status = data.status;
        } else {
            state.success_message = "";
        }
    },

    GET_SINGLE_PERMISSION: (state, data) => {
        state.permission = data.data;
    },

    UPDATE_PERMISSION: (state, data) => {
        if (state.permissions.push(data.data)) {
            state.success_message = data.data.message;
            state.success_status = data.status;
        } else {
            state.success_message = "";
        }
    },

    DELETE_PERMISSION: (state, { id, data }) => {
        if (id) {
            state.menus = state.permissions.filter((item) => {
                return id !== item.id;
            });

            state.success_message = data.data.message;
            state.success_status = data.status;
        } else {
            state.success_message = "";
        }
    },

    SET_ERROR(state, { errors, errorStatus }) {
        state.errors = errors;
        state.error_status = errorStatus;
    }
};

/* -------------------------------------------------------------------------- */
/*                               Actions Define                               */
/* -------------------------------------------------------------------------- */
const actions = {
    /*start get all parents*/
    async GetAllPermission({ commit }) {
        try {
            const result = await http().get("v1/admin/permission", {
                params: {
                    pagination: false
                }
            });
            commit("GET_ALL_PERMISSION", result.data.data);
        } catch (err) {
            const errors = err.response.data.errors;
            const errorStatus = err.response.status;
            commit("SET_ERROR", { errors, errorStatus });
            throw err; // Re-throw the error to propagate it to the caller
        }
    },
    /*end get all parents*/

    /*start store permission*/
    StorePermission: ({ commit }, data) => {
        return http()
            .post("v1/admin/permission", data)
            .then((result) => {
                commit("STORE_PERMISSION", result);
                return result
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err; // Re-throw the error to propagate it to the caller
            });
    },
    /*end store permission*/

    /*start get single menu*/
    GetSinglePermission: ({ commit }, id) => {
        return http()
            .get(`v1/admin/permission/${id}`)
            .then((result) => {
                commit("GET_SINGLE_PERMISSION", result.data);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err; // Re-throw the error to propagate it to the caller
            });
    },
    /*end get single menu*/

    /*start update menus*/
    UpdatePermission: ({ commit }, { id, data }) => {
        return http()
            .put(`v1/admin/permission/${id}`, data)
            .then((result) => {
                commit("UPDATE_PERMISSION", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err; // Re-throw the error to propagate it to the caller
            });
    },
    /*end update menus*/

    /*start destroy menu*/
    DestroyPermission: ({ commit }, id) => {
        return http()
            .delete(`v1/admin/permission/${id}`)
            .then((result) => {
                commit("DELETE_PERMISSION", { id: id, data: result.data });
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err; // Re-throw the error to propagate it to the caller
            });
    },
    /*end destroy menu*/
};

/* -------------------------------------------------------------------------- */
/*                               Getters Define                               */
/* -------------------------------------------------------------------------- */
const getters = {};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};