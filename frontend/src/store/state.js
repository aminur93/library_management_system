export default {
    //Local development url start
    apiUrl: "http://localhost:8000/api/",
    serverPath: "http://localhost:8000",
    //Local development url end

    //production server url start
    //production server url end

    //root state data property
    token: localStorage.getItem("token") || "",
    user: localStorage.getItem("user") || "",
    role: localStorage.getItem("role") || "",
    permissions: [],
    menus: [],
    success_message: '',
    errors: {},
    error_message: '',
    error_status: '',
    success_status: ''
}