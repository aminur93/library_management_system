<script>
import {mapState} from "vuex";
import {http} from "@/service/HttpService";
import permissionMixins from "@/mixins/PermissionMixins";

export default {
  name: "BorrowBookIndex",
  mixins: [permissionMixins],

  data(){
    return{
      totalBorrowBooks: 0,
      borrowBooks: [],
      loading: true,
      options: {},
      search: '',
      headers: [
        { title: 'ID', key: 'id', sortable: false },
        { title: 'Book', key: 'book_id' },
        { title: 'Member', key: 'member_id' },
        { title: 'Borrow Date', key: 'borrow_date' },
        { title: 'Return Date', key: 'return_date' },
        { title: 'Status', key: 'status' },
        { title: 'Actions', key: 'actions', align: 'center', sortable: false },
      ],
    }
  },

  computed: {
    startIndex() {
      let currentPage = this.options.page;
      let itemsPerPage = this.options.itemsPerPage;

      return (currentPage - 1) * itemsPerPage + 1;
    },

    hasDeletePermission() {
      return this.checkPermission('borrowBook-delete');
    },

    ...mapState({
      message: state => state.borrow_book.success_message,
      errors: state => state.borrow_book.errors,
      success_status: state => state.borrow_book.success_status,
      error_status: state => state.borrow_book.error_status
    })
  },

  watch: {
    options: {
      handler () {
        this.getAllBorrowBooks()
      },
      deep: true,
    },

    search: {
      handler () {
        this.getAllBorrowBooks()
      },
    },
  },

  mounted() {
    this.getAllBorrowBooks();
  },

  methods: {
    getAllBorrowBooks(){
      this.loading = true

      const { sortBy, sortDesc, page, itemsPerPage } = this.options

      http().get('http://localhost:8000/api/v1/admin/borrow-book', {
        params: {
          sortBy: sortBy[0],
          sortDesc: sortDesc,
          page: page,
          itemsPerPage: itemsPerPage,
          search: this.search
        }
      }).then((result) => {
        this.borrowBooks = result.data.data.data;
        this.totalBorrowBooks = result.data.data.total;
        this.loading = false;
      }).catch((err) => {
        console.log(err);
      })
    },

    calculateIndex(item) {
      return this.startIndex + item;
    },

    deleteBorrowBook: async function(id){
      try {
        await this.$store.dispatch("borrow_book/DeleteBorrowBook", id).then(() => {
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

            this.getAllBooks();
          }
        })
      }catch (e) {
        if (this.error_status === 403)
        {
          this.$swal.fire({
            icon: 'error',
            text: 'Permission denied',
          });
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
  <div id="index">
    <v-row class="mx-5">

      <v-col cols="12" class="pa-6">

        <v-row wrap>
          <v-col cols="6">
            <h1 :class="['text-subtitle-2', 'text-grey', 'mt-5']">Borrow Book</h1>
          </v-col>
        </v-row>

        <v-row wrap>
          <v-col cols="12">
            <v-card elevation="8">
              <v-row>
                <v-col col="6">
                  <v-card-title :class="['text-subtitle-1']">All Borrow Book Lists</v-card-title>
                </v-col>

                <v-col cols="6">
                  <v-card-actions class="justify-end">
                    <v-btn color="success" @click="navigateWithPermission('borrowBook-create', '/add-borrow-book')">
                      <v-icon small left>mdi-plus</v-icon>
                      <span>Add New</span>
                    </v-btn>
                  </v-card-actions>
                </v-col>
              </v-row>

              <v-divider></v-divider>

              <v-card-text>
                <v-card-title class="d-flex align-center pe-2" style="justify-content: space-between">
                  <h1 :class="['text-subtitle-1', 'text-black']">Borrow Book</h1>

                  <v-spacer></v-spacer>

                  <v-text-field
                      v-model="search"
                      density="compact"
                      label="Search"
                      prepend-inner-icon="mdi-magnify"
                      variant="solo-filled"
                      flat
                      hide-details
                      single-line
                  ></v-text-field>
                </v-card-title>


                <v-data-table-server
                    :headers="headers"
                    :items="borrowBooks"
                    :search="search"
                    v-model:options="options"
                    :items-length="totalBorrowBooks"
                    :loading="loading"
                    item-value="name"
                    class="elevation-4"
                    fixed-header
                >
                  <template v-slot:[`item.id`]="{ index }">
                    <td>{{ calculateIndex(index) }}</td>
                  </template>

                  <template v-slot:[`item.book_id`]="{ item }">
                    <td>{{ item.book.title }}</td>
                  </template>

                  <template v-slot:[`item.member_id`]="{ item }">
                    <td>{{ item.member.first_name }}</td>
                  </template>


                  <template v-slot:[`item.actions`]="{ item }">
                    <v-row align="center" justify="center">
                      <td :class="['mx-2']">
                        <v-btn @click="navigateWithPermission('borrowBook-edit', `/edit-borrow-book/${item.id}`)" color="warning" icon="mdi-pencil" size="x-small"></v-btn>
                      </td>

                      <td v-if="hasDeletePermission">
                        <v-btn color="red" icon="mdi-delete" size="x-small" @click="deleteBorrowBook(item.id)"></v-btn>
                      </td>
                    </v-row>
                  </template>
                </v-data-table-server>

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