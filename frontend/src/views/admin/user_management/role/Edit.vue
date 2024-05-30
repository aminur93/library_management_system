<script>
import {mapActions, mapGetters, mapState} from "vuex";
import router from "@/router";

export default {
  name: "RoleEdit",

  data(){
    return{
      errors: {}
    }
  },

  computed: {
    ...mapState({
      getEditRole: state => state.roles.role,
      permissions: state => state.permission.permissions,
      message: state => state.roles.success_message,
      errors: state => state.roles.errors,
      success_status: state => state.roles.success_status,
      error_status: state => state.roles.error_status
    }),

    ...mapGetters({
        editRolePermissionData: 'roles/rolePermissions'
    }),

    rolePermissions: {
      get(){
        return this.editRolePermissionData
      },
      set(value){
        this.$store.commit('roles/UPDATE_VALUE', value)
      }
    }
  },

  mounted() {
    this.getAllPermission();
    this.getSingleRole(this.$route.params.id);
  },

  methods: {
    ...mapActions({
        getAllPermission: "permission/GetAllPermission",
        getSingleRole: "roles/editRole",
    }),

    editRole: async function()
    {
      try {
        let id = this.$route.params.id;

        let formData = new FormData();

        formData.append('name', this.getEditRole.name);
        formData.append("permission[]", this.rolePermissions);

        await this.$store.dispatch('roles/UpdateRole', {id:id, data:formData}).then(() => {
          if (this.success_status === 200)
          {
            this.$swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: this.message,
              showConfirmButton: false,
              timer: 1500
            });

            this.getSingleRole(id);

            setTimeout(function () {
              router.push({path: '/role'});
            },2000)
          }

        });
      }catch (e) {
        console.log(e);
      }
    }
  }
}
</script>

<template>
  <div id="edit_role">
    <v-row class="mx-5 mt-5">
      <v-col cols="12">
        <v-row>
          <v-col cols="12" md="12" sm="12" lg="12">
            <v-card>
              <v-card-title><h3>Edit Role</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="editRole">
                  <v-col cols="12">
                    <v-row wrap>
                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="text"
                            v-model="getEditRole.name"
                            label="Role name"
                            persistent-hint
                            variant="outlined"
                            required
                        ></v-text-field>
                        <p v-if="errors.name" class="error custom_error">{{errors.name[0]}}</p>
                      </v-col>

                      <strong :class="['ml-3']">Permission: </strong>
                      <v-divider :class="['mt-5', 'ml-3', 'mr-2']"></v-divider>

                      <v-row>
                        <v-col cols="3" v-for="permission in permissions" :key="permission.id">
                            <v-checkbox
                                v-model="rolePermissions"
                                color="green"
                                :value="permission.id"
                                :label="permission.name"
                                hide-details>
                            </v-checkbox>
                        </v-col>
                      </v-row>
                    </v-row>

                    <v-row wrap>
                      <v-col cols="12" md="8" sm="12" lg="12" :class="['d-flex', 'justify-end']">
                        <v-btn
                            flat
                            color="primary"
                            class="custom-btn mr-2"
                            router
                            to="/role"
                        >
                          Back
                        </v-btn>
                        <v-btn
                            flat
                            color="success"
                            type="submit"
                            class="custom-btn mr-2"
                        >
                          Submit
                        </v-btn>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>

<style scoped>
.error{
  color: red;
}
</style>