<script>
import {mapActions, mapState} from "vuex";
import router from "@/router";

export default {
  name: "RoleCreate",

  data(){
    return{
      add_role: {
        name: '',
        permission: []
      }
    }
  },

  computed: {
    ...mapState({
      permissions: state => state.permission.permissions,
      message: state => state.roles.success_message,
      errors: state => state.roles.errors,
      success_status: state => state.roles.success_status,
      error_status: state => state.roles.error_status
    })
  },

  mounted() {
    this.getAllPermission();
  },

  methods: {
    ...mapActions({
      "getAllPermission": "permission/GetAllPermission"
    }),

    addRole: async function(){

      try {

        let formData = new FormData();

        formData.append('name', this.add_role.name);

        formData.append("permission[]", this.add_role.permission);

        await this.$store.dispatch("roles/StoreRole", formData).then(() => {
          if (this.success_status === 201)
          {
            this.$swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: this.message,
              showConfirmButton: false,
              timer: 1500
            });

            this.add_role = {};

            setTimeout(function () {
              router.push({path: '/role'});
            },2000)
          }
        })

      }catch (e) {
        console.log(e);
      }
    }
  }
}
</script>

<template>
  <div id="add_role">
    <v-row class="mx-5 mt-5">
      <v-col cols="12">
        <v-row>
          <v-col cols="12" md="12" sm="12" lg="12">
            <v-card>
              <v-card-title><h3>Add Role</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="addRole">
                  <v-col cols="12">
                    <v-row wrap>
                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="text"
                            v-model="add_role.name"
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
                              v-model="add_role.permission"
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