/* -------------------------------------------------------------------------- */
/*                                states Define                               */
/* -------------------------------------------------------------------------- */
import {http} from "@/service/HttpService";

const state = {
    members: [],
    member: {},
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
    GET_ALL_MEMBERS: (state, data) => {
        state.members = data;
    },

    STORE_MEMBER: (state, data) => {
        if (state.members.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }else {
            state.success_message = '';
        }
    },

    GET_SINGLE_MEMBER: (state, data) => {
        state.member = data;
    },

    UPDATE_MEMBER: (state, data) => {
        if (state.members.push(data.data))
        {
            state.success_message = data.data.message;
            state.success_status = data.status;
        }
    },

    DELETE_MEMBER: (state, {id, data}) => {
        if (id)
        {
            state.members = state.members.filter(item => {
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
    /*get all member start*/
    async GetAllMember({ commit }) {
        try {
            const result = await http().get("v1/admin/member", {
                params: {
                    pagination: false
                }
            });
            commit("GET_ALL_MEMBERS", result.data.data);
        } catch (err) {
            const errors = err.response.data.errors;
            const errorStatus = err.response.status;
            commit("SET_ERROR", { errors, errorStatus });
            throw err; // Re-throw the error to propagate it to the caller
        }
    },
    /*get all member end*/

    /*store member start*/
    StoreMember: ({commit}, data) => {
        return http()
            .post("v1/admin/member", data)
            .then((result) => {
                commit("STORE_MEMBER", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*store member end*/

    /*get single member start*/
    GetSingleMember: ({commit}, id) => {
        return http()
            .get(`v1/admin/member/${id}`)
            .then((result) => {
                commit("GET_SINGLE_MEMBER", result.data.data);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*get single member end*/

    /*update member start*/
    UpdateMember: ({commit}, {id, data}) => {
        return http()
            .put(`v1/admin/member/${id}`, data)
            .then((result) => {
                commit("UPDATE_MEMBER", result);
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*update member end*/

    /*destroy member start*/
    DeleteMember: ({commit}, id) => {
        return http()
            .delete(`v1/admin/member/${id}`)
            .then((result) => {
                commit("DELETE_MEMBER", {id:id, data:result});
            })
            .catch((err) => {
                const errors = err.response.data.errors;
                const errorStatus = err.response.status;
                commit("SET_ERROR", { errors, errorStatus });
                throw err;
            })
    },
    /*destroy member end*/
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