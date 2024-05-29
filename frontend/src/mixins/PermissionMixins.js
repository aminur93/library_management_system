import {http} from "@/service/HttpService";

export default {

    methods: {
        async checkPermission(permissionName) {
            try {
                const response = await http().get('v1/admin/check-permission', {
                    params: { permission: permissionName }
                });
                return response.status === 200;
            } catch (error) {
                if (error.response && error.response.status === 403) {
                    return false;
                } else {
                    console.error("Permission check failed:", error);
                    throw error;
                }
            }
        },

        async navigateWithPermission(permissionName, path) {
            try {
                const hasPermission = await this.checkPermission(permissionName);
                if (hasPermission) {
                    this.$router.push(path);
                } else {
                    this.$router.push('/404');
                }
            } catch (error) {
                this.$router.push('/404');
            }
        },
    }
}