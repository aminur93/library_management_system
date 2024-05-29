<script>
import {mapActions, mapState} from "vuex";
import router from "@/router";

export default {
  name: "BookEdit",

  data(){
    return{

    }
  },

  computed: {
    ...mapState({
      editBook: state => state.book.book,
      authors: state => state.author.authors,
      message: state => state.book.success_message,
      errors: state => state.book.errors,
      success_status: state => state.book.success_status,
      error_status: state => state.book.error_status
    })
  },

  mounted() {
    this.getSingleBook(this.$route.params.id);
    this.getAllAuthors();
  },

  methods: {
    ...mapActions({
      getSingleBook: "book/GetSingleBook",
      getAllAuthors: "author/GetAllAuthor"
    }),

    updateBook: async function(){
      try {
        let id = this.$route.params.id;

        let formData = new FormData();

        formData.append('author_id', this.editBook.author_id);
        formData.append('title', this.editBook.title);
        formData.append('isbn', this.editBook.isbn);
        formData.append('published_date', this.editBook.published_date);
        formData.append('available_copies', this.editBook.available_copies);
        formData.append('total_copies', this.editBook.total_copies);

        await this.$store.dispatch('book/UpdateBook', {id:id, data:formData}).then(() => {

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

            this.getSingleBook(id);

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
  <div id="edit">
    <v-row class="mx-5 mt-5">
      <v-col cols="12">
        <v-row>
          <v-col cols="12" md="12" sm="12" lg="12">
            <v-card>
              <v-card-title><h3>Edit Book</h3></v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-form v-on:submit.prevent="updateBook">
                  <v-col cols="12">
                    <v-row wrap>

                      <v-col cols="12" md="8" sm="12" lg="12">
                        <v-select
                            variant="outlined"
                            v-model="editBook.author_id"
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
                            v-model="editBook.title"
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
                            v-model="editBook.isbn"
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
                            v-model="editBook.published_date"
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
                                v-model="editBook.available_copies"
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
                                v-model="editBook.total_copies"
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