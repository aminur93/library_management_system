<script>
import {mapActions, mapState} from "vuex";
import router from "@/router";

export default {
  name: "BookCreate",

  data(){
    return{
      add_book: {
        author_id: null,
        title: '',
        isbn: '',
        published_date: '',
        available_copies: '',
        total_copies: ''
      }
    }
  },

  computed: {
    ...mapState({
      authors: state => state.author.authors,
      message: state => state.book.success_message,
      errors: state => state.book.errors,
      success_status: state => state.book.success_status,
      error_status: state => state.book.error_status
    })
  },

  mounted() {
    this.getAllAuthors();
  },

  methods: {
    ...mapActions({
      getAllAuthors: "author/GetAllAuthor"
    }),

    addBook: async function(){
      try{
        let formData = new FormData();

        formData.append('author_id', this.add_book.author_id);
        formData.append('title', this.add_book.title);
        formData.append('isbn', this.add_book.isbn);
        formData.append('published_date', this.add_book.published_date);
        formData.append('available_copies', this.add_book.available_copies);
        formData.append('total_copies', this.add_book.total_copies);

        await this.$store.dispatch('book/StoreBook', formData).then(() => {

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
              router.push({path: '/book'});
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
              <v-card-title><h3>Add Book</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="addBook">
                  <v-col cols="12">
                    <v-row wrap>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-select
                            variant="outlined"
                            v-model="add_book.author_id"
                            :items="authors"
                            item-title="name"
                            item-value="id"
                            label="select Author"
                        ></v-select>
                        <p v-if="errors.author_id" class="error custom_error">{{errors.author_id[0]}}</p>
                      </v-col>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="text"
                            v-model="add_book.title"
                            label="Title"
                            persistent-hint
                            variant="outlined"
                            required
                        ></v-text-field>
                        <p v-if="errors.title" class="error custom_error">{{errors.title[0]}}</p>
                      </v-col>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="text"
                            v-model="add_book.isbn"
                            label="ISBN"
                            persistent-hint
                            variant="outlined"
                            required
                        ></v-text-field>
                        <p v-if="errors.isbn" class="error custom_error">{{errors.isbn[0]}}</p>
                      </v-col>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-text-field
                            type="date"
                            v-model="add_book.published_date"
                            label="Published Date"
                            persistent-hint
                            variant="outlined"
                            required
                        ></v-text-field>
                        <p v-if="errors.published_date" class="error custom_error">{{errors.published_date[0]}}</p>
                      </v-col>

                      <v-col cols="12" class="d-flex">
                        <v-row wrap>
                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="text"
                                v-model="add_book.available_copies"
                                label="Available Copies"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.available_copies" class="error custom_error">{{errors.available_copies[0]}}</p>
                          </v-col>

                          <v-col cols="12" md="6" sm="6" lg="6">
                            <v-text-field
                                type="text"
                                v-model="add_book.total_copies"
                                label="Total Copies"
                                persistent-hint
                                variant="outlined"
                                required
                            ></v-text-field>
                            <p v-if="errors.total_copies" class="error custom_error">{{errors.total_copies[0]}}</p>
                          </v-col>
                        </v-row>
                      </v-col>

                      <v-row wrap>
                        <v-col cols="12" md="8" sm="12" lg="12" :class="['d-flex', 'justify-end']">
                          <v-btn
                              flat
                              color="primary"
                              class="custom-btn mr-2"
                              router
                              to="/book"
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