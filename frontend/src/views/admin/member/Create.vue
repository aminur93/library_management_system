<script>
import {mapState} from "vuex";
import router from "@/router";

export default {
  name: "MemberCreate",

  data(){
    return{
      add_member: {
        first_name: '',
        last_name: '',
        email: '',
        phone_number: '',
        registration_date: null
      }
    }
  },

  computed: {
    ...mapState({
      message: state => state.member.success_message,
      errors: state => state.member.errors,
      success_status: state => state.member.success_status,
      error_status: state => state.member.error_status
    })
  },

  methods: {
    addMember: async function(){
      try {
        let formData = new FormData();

        formData.append('first_name', this.add_member.first_name);
        formData.append('last_name', this.add_member.last_name);
        formData.append('email', this.add_member.email);
        formData.append('phone_number', this.add_member.phone_number);
        formData.append('registration_date', this.add_member.registration_date);

        await this.$store.dispatch('member/StoreMember', formData).then(() => {

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

            this.add_author = {};

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
  }
}
</script>

<template>
  <div id="create">
    <v-row class="mx-5 mt-5">
      <v-col cols="12">
        <v-row>
          <v-col cols="12" md="12" sm="12" lg="12">
            <v-card>
              <v-card-title><h3>Add Member</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="addMember">
                  <v-col cols="12">
                    <v-row wrap>

                      <v-col cols="12" class="d-flex">
                        <v-row wrap>
                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="text"
                                v-model="add_member.first_name"
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
                                v-model="add_member.last_name"
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
                                v-model="add_member.email"
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
                                v-model="add_member.phone_number"
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
                            v-model="add_member.registration_date"
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
.error{
  color: red;
}
</style>