<script>
import {mapActions, mapState} from "vuex";
import router from "@/router";

export default {
  name: "MemberEdit",

  data(){
    return{

    }
  },

  computed: {
    ...mapState({
      editMember: state => state.member.member,
      message: state => state.member.success_message,
      errors: state => state.member.errors,
      success_status: state => state.member.success_status,
      error_status: state => state.member.error_status
    })
  },

  mounted() {
    this.getSingleMember(this.$route.params.id);
  },

  methods: {
    ...mapActions({
      getSingleMember: "member/GetSingleMember"
    }),

    updateMember: async function(){
      try {
        let id = this.$route.params.id;
        let formData = new FormData();

        formData.append('first_name', this.editMember.first_name);
        formData.append('last_name', this.editMember.last_name);
        formData.append('email', this.editMember.email);
        formData.append('phone_number', this.editMember.phone_number);
        formData.append('registration_date', this.editMember.registration_date);

        await this.$store.dispatch('member/UpdateMember', {id:id, data:formData}).then(() => {

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

            this.getSingleMember(id);

            setTimeout(function () {
              router.push({path: '/member'});
            },2000)
          }
        })
      }catch (e) {
        if (this.error_status === 422)
        {
          console.log('error');
        }else {
          this.$swal.fire({
            icon: 'error',
            text: 'Oops',
            title: 'Something wen wrong!!!',
          });
        }
      }
    }
  },
}
</script>

<template>
  <div id="edit">
    <v-row class="mx-5 mt-5">
      <v-col cols="12">
        <v-row>
          <v-col cols="12" md="12" sm="12" lg="12">
            <v-card>
              <v-card-title><h3>Edit Member</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="updateMember">
                  <v-col cols="12">
                    <v-row wrap>

                      <v-col cols="12" class="d-flex">
                        <v-row wrap>
                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="text"
                                v-model="editMember.first_name"
                                label="First Name"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.first_name" class="error custom_error">{{errors.first_name[0]}}</p>
                          </v-col>

                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="text"
                                v-model="editMember.last_name"
                                label="Last Name"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.last_name" class="error custom_error">{{errors.last_name[0]}}</p>
                          </v-col>
                        </v-row>
                      </v-col>

                      <v-col cols="12" class="d-flex">
                        <v-row wrap>
                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="text"
                                v-model="editMember.email"
                                label="Email"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.email" class="error custom_error">{{errors.email[0]}}</p>
                          </v-col>

                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="text"
                                v-model="editMember.phone_number"
                                label="Phone Number"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.phone_number" class="error custom_error">{{errors.phone_number[0]}}</p>
                          </v-col>
                        </v-row>
                      </v-col>

                      <v-col cols="12" md="12" sm="12" lg="12">
                        <v-text-field
                            type="date"
                            v-model="editMember.registration_date"
                            label="Registration Date"
                            persistent-hint
                            variant="outlined"
                        >
                        </v-text-field>
                        <p v-if="errors.registration_date" class="error custom_error">{{errors.registration_date[0]}}</p>
                      </v-col>

                      <v-row wrap>
                        <v-col cols="12" md="8" sm="12" lg="12" :class="['d-flex', 'justify-end']">
                          <v-btn
                              flat
                              color="primary"
                              class="custom-btn mr-2"
                              router
                              to="/member"
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

</style>