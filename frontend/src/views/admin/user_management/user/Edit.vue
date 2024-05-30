<script>
import {mapActions, mapState} from "vuex";
import router from "@/router";

export default {
  name: "EditUser",

  data(){
    return{
      password: '',
      password_confirmation: '',

      // PasswordRules: [ v => !!v || "Password is required", v => (v && v.length >= 8) || "password must be valid" ],
      // PasswordRules2: [ v => !!v || "Password incorrect" ],
    }
  },

  computed: {
    ...mapState({
      getSingleEditUser: state => state.users.user,
      roles: state => state.roles.roles,
      message: state => state.users.success_message,
      errors: state => state.users.errors,
      success_status: state => state.users.success_status,
      error_status: state => state.users.error_status
    })
  },

  mounted() {
    this.getAllRole();
    this.getEditUser(this.$route.params.id);
  },

  methods: {
    ...mapActions({
      getAllRole: "roles/GetAllRole",
      getEditUser: "users/GetSingleUser"
    }),

    editUser: async function(){
      try{

        let id = this.$route.params.id;

        let formData = new FormData();

        formData.append('name', this.getSingleEditUser.name);
        formData.append('email', this.getSingleEditUser.email);
        formData.append('password', this.password);
        formData.append('password_confirmation', this.password_confirmation);
        formData.append('role[]', this.getSingleEditUser.role_id);

        await this.$store.dispatch("users/UpdateUser", {id:id, data:formData}).then(() => {

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

            this.getEditUser(id);

            setTimeout(function () {
              router.push({path: '/user'});
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
  <div id="edit_user">
    <v-row class="mx-5 mt-5">
      <v-col cols="12">
        <v-row>
          <v-col cols="12" md="12" sm="12" lg="12">
            <v-card>
              <v-card-title><h3>Edit User</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="editUser">
                  <v-col cols="12">
                    <v-row wrap>
                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="text"
                            v-model="getSingleEditUser.name"
                            label="Name"
                            persistent-hint
                            variant="outlined"
                            required
                        ></v-text-field>
                        <p v-if="errors.name" class="error custom_error">{{errors.name[0]}}</p>
                      </v-col>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="text"
                            v-model="getSingleEditUser.email"
                            label="Email"
                            persistent-hint
                            variant="outlined"
                            required
                        ></v-text-field>
                        <p v-if="errors.email" class="error custom_error">{{errors.email[0]}}</p>
                      </v-col>

                      <v-col cols="12" class="d-flex">
                        <v-row wrap>
                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="password"
                                v-model="password"
                                label="Password"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.password" class="error custom_error">{{errors.password[0]}}</p>
                          </v-col>

                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="password"
                                v-model="password_confirmation"
                                label="Password Confirmation"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.password_confirmation" class="error custom_error">{{errors.password_confirmation[0]}}</p>
                          </v-col>
                        </v-row>
                      </v-col>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-select
                            v-model="getSingleEditUser.role_id"
                            :items="roles"
                            item-title="name"
                            item-value="id"
                            label="Select Role"
                            variant="outlined"
                            chips
                            multiple
                        ></v-select>
                        <p v-if="errors.role" class="error custom_error">{{errors.role[0]}}</p>
                      </v-col>

                      <v-row wrap>
                        <v-col cols="12" md="8" sm="12" lg="12" :class="['d-flex', 'justify-end']">
                          <v-btn
                              flat
                              color="primary"
                              class="custom-btn mr-2"
                              router
                              to="/user"
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