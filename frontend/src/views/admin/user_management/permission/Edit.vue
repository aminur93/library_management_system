<script>
import {mapActions, mapState} from "vuex";
import router from "@/router";

export default {
  name: "PermissionEdit",

  data(){
    return{

    }
  },

  computed: {
    ...mapState({
      singlePermission: state => state.permission.permission,
      message: state => state.permission.success_message,
      errors: state => state.permission.errors,
      success_status: state => state.permission.success_status,
      error_status: state => state.permission.error_status
    })
  },

  mounted() {
    this.getSinglePermission(this.$route.params.id);
  },

  methods: {
    ...mapActions({
      "getSinglePermission": "permission/GetSinglePermission"
    }),

    updatePermission: async function(){
      try {

          let id = this.$route.params.id;
          let formData = new FormData();


          formData.append('name', this.singlePermission.name);

          await this.$store.dispatch('permission/UpdatePermission', {id:id, data:formData}).then(() => {
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

                this.add_permission = {};

                setTimeout(function () {
                  router.push({path: '/permission'});
                },2000)
              }
          })
      }catch (e) {
        if (this.error_status === 422)
        {
          console.log('error')
        }else {
          this.$swal.fire({
            icon: 'error',
            text: 'Oops',
            title: 'Something wen wrong!!!',
          });
        }

      }
    }
  }
}
</script>

<template>
  <div id="edit_permission">
    <v-row class="mx-5 mt-5">
      <v-col cols="12">
        <v-row>
          <v-col cols="12" md="12" sm="12" lg="12">
            <v-card>
              <v-card-title><h3>Edit Permission</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="updatePermission">
                  <v-col cols="12">
                    <v-row wrap>
                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="text"
                            v-model="singlePermission.name"
                            label="Permission name"
                            persistent-hint
                            variant="outlined"
                            required
                        ></v-text-field>
                        <p v-if="errors.name" class="error custom_error">{{errors.name[0]}}</p>
                      </v-col>

                    </v-row>

                    <v-row wrap>
                      <v-col cols="12" md="8" sm="12" lg="12" :class="['d-flex', 'justify-end']">
                        <v-btn
                            flat
                            color="primary"
                            class="custom-btn mr-2"
                            router
                            to="/permission"
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