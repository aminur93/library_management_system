<script>
import {mapActions, mapState} from "vuex";
import router from "@/router";

export default {
  name: "BorrowBookCreate",

  data(){
    return{
      add_borrow_book: {
        book_id: null,
        member_id: null,
        borrow_date: '',
        return_date: '',
        status: null
      },

      borrowStatus: [
        {'name': 'Borrowed'},
        {'name': 'Returned'},
        {'name': 'Overdue'}
      ]
    }
  },

  computed: {
    ...mapState({
      books: state => state.book.books,
      members: state => state.member.members,
      message: state => state.borrow_book.success_message,
      errors: state => state.borrow_book.errors,
      error_message: state => state.borrow_book.error_message,
      success_status: state => state.borrow_book.success_status,
      error_status: state => state.borrow_book.error_status
    })
  },

  mounted() {
    this.getAllBooks();
    this.getAllMembers();
  },

  methods: {
    ...mapActions({
      getAllBooks: "book/GetAllBook",
      getAllMembers: "member/GetAllMember"
    }),

    addBorrowBook: async function(){
      try {

        let formData = new FormData();

        formData.append('book_id', this.add_borrow_book.book_id);
        formData.append('member_id', this.add_borrow_book.member_id);
        formData.append('borrow_date', this.add_borrow_book.borrow_date);
        formData.append('return_date', this.add_borrow_book.return_date);
        formData.append('status', this.add_borrow_book.status);

        await this.$store.dispatch('borrow_book/StoreBorrowBook', formData).then(() => {

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

            this.add_borrow_book = {};

            setTimeout(function () {
              router.push({path: '/borrow-book'});
            },2000)
          }
        })

      }catch (e) {
        if (this.error_status === 422)
        {
          console.log('error');
        }

        if (this.error_status === 500)
        {
          this.$swal.fire({
            icon: 'error',
            text: 'Oops',
            title: this.error_message,
          });
        }

        if (this.error_status === 400)
        {
          this.$swal.fire({
            icon: 'error',
            text: 'Oops',
            title: this.error_message,
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
              <v-card-title><h3>Add Borrow Book</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="addBorrowBook">
                  <v-col cols="12">
                    <v-row wrap>

                      <v-col cols="12" class="d-flex">
                        <v-row wrap>
                          <v-col cols="12" md="8" sm="12" lg="6">
                            <v-select
                                variant="outlined"
                                v-model="add_borrow_book.book_id"
                                :items="books"
                                item-title="title"
                                item-value="id"
                                label="select Book"
                            ></v-select>
                            <p v-if="errors.book_id" class="error custom_error">{{errors.book_id[0]}}</p>
                          </v-col>

                          <v-col cols="12" md="8" sm="12" lg="6">
                            <v-select
                                variant="outlined"
                                v-model="add_borrow_book.member_id"
                                :items="members"
                                item-title="first_name"
                                item-value="id"
                                label="select Member"
                            ></v-select>
                            <p v-if="errors.member_id" class="error custom_error">{{errors.member_id[0]}}</p>
                          </v-col>
                        </v-row>
                      </v-col>

                      <v-col cols="12" class="d-flex">
                        <v-row wrap>
                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="date"
                                v-model="add_borrow_book.borrow_date"
                                label="Borrow Date"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.borrow_date" class="error custom_error">{{errors.borrow_date[0]}}</p>
                          </v-col>

                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="date"
                                v-model="add_borrow_book.return_date"
                                label="Return Date"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.return_date" class="error custom_error">{{errors.return_date[0]}}</p>
                          </v-col>
                        </v-row>
                      </v-col>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-select
                            variant="outlined"
                            v-model="add_borrow_book.status"
                            :items="borrowStatus"
                            item-title="name"
                            item-value="name"
                            label="select Status"
                        ></v-select>
                        <p v-if="errors.status" class="error custom_error">{{errors.status[0]}}</p>
                      </v-col>

                      <v-row wrap>
                        <v-col cols="12" md="8" sm="12" lg="12" :class="['d-flex', 'justify-end']">
                          <v-btn
                              flat
                              color="primary"
                              class="custom-btn mr-2"
                              router
                              to="/borrow-book"
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