<script>
import {mapState} from "vuex";
import {http} from "@/service/HttpService";
import permissionMixins from "@/mixins/PermissionMixins";

export default {
  name: "LeaveUser",
  mixins: [permissionMixins],

  data(){
    return{
      totalUsers: 0,
      users: [],
      loading: true,
      options: {},
      search: '',
      headers: [
        { title: 'ID', key: 'id', sortable: false },
        { title: 'Name', key: 'name' },
        { title: 'Email', key: 'email' },
        { title: 'Role', key: 'role' },
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
      return this.checkPermission('user-delete');
    },

    ...mapState({
      message: state => state.users.success_message,
      errors: state => state.users.errors,
      success_status: state => state.users.success_status,
      error_status: state => state.users.error_status
    })
  },

  watch: {
    options: {
      handler () {
        this.getAllUsers()
      },
      deep: true,
    },

    search: {
      handler () {
        this.getAllUsers()
      },
    },
  },

  mounted() {
    this.getAllUsers();
  },

  methods: {
    getAllUsers(){
      this.loading = true

      const { sortBy, sortDesc, page, itemsPerPage } = this.options

      http().get('http://localhost:8000/api/v1/admin/user', {
        params: {
          sortBy: sortBy[0],
          sortDesc: sortDesc,
          page: page,
          itemsPerPage: itemsPerPage,
          search: this.search
        }
      }).then((result) => {
        this.users = result.data.data.data;
        this.totalUsers = result.data.data.total;
        this.loading = false;
      }).catch((err) => {
        console.log(err);
      })
    },

    calculateIndex(item) {
      return this.startIndex + item;
    },

    deleteUser: async function(id){
      try {
        await this.$store.dispatch("users/DeleteUser", id).then(() => {
          if (this.success_status === 200)
          {
            this.$swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: 'User destroy successful',
              showConfirmButton: false,
              timer: 1500
            });

            this.getAllUsers();
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
  <div id="user">
    <v-row class="mx-5">

      <v-col cols="12" class="pa-6">

        <v-row wrap>
          <v-col cols="6">
            <h1 :class="['text-subtitle-2', 'text-grey', 'mt-5']">User</h1>
          </v-col>
        </v-row>

        <v-row wrap>
          <v-col cols="12">
            <v-card elevation="8">
              <v-row>
                <v-col col="6">
                  <v-card-title :class="['text-subtitle-1']">All User Lists</v-card-title>
                </v-col>

                <v-col cols="6">
                  <v-card-actions class="justify-end">
                    <v-btn color="success" @click="navigateWithPermission('user-create', '/add-user')">
                      <v-icon small left>mdi-plus</v-icon>
                      <span>Add New</span>
                    </v-btn>
                  </v-card-actions>
                </v-col>
              </v-row>

              <v-divider></v-divider>

              <v-card-text>
                <v-card-title class="d-flex align-center pe-2" style="justify-content: space-between">
                  <h1 :class="['text-subtitle-1', 'text-black']">Users</h1>

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
                    :items="users"
                    :search="search"
                    v-model:options="options"
                    :items-length="totalUsers"
                    :loading="loading"
                    item-value="name"
                    class="elevation-4"
                    fixed-header
                >
                  <template v-slot:[`item.id`]="{ index }">
                    <td>{{ calculateIndex(index) }}</td>
                  </template>

                  <template v-slot:[`item.role`]="{ item }">
                    <td>
                      <v-chip variant="elevated" color="success" :class="['text-center']">
                        {{ item.roles[0].name }}
                      </v-chip>
                    </td>
                  </template>


                  <template v-slot:[`item.actions`]="{ item }">
                    <v-row align="center" justify="center">
                      <td :class="['mx-2']">
                        <v-btn @click="navigateWithPermission('user-edit', `/edit-user/${item.id}`)" color="warning" icon="mdi-pencil" size="x-small"></v-btn>
                      </td>

                      <td v-if="hasDeletePermission">
                        <v-btn color="red" icon="mdi-delete" size="x-small" @click="deleteUser(item.id)"></v-btn>
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