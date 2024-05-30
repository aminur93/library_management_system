<script>
import {http} from "@/service/HttpService";
import {mapState} from "vuex";
import permissionMixins from "@/mixins/PermissionMixins";

export default {
  name: "PermissionIndex",
  mixins: [permissionMixins],

  data(){
    return{
      totalPermissions: 0,
      permissions: [],
      loading: true,
      options: {},
      search: '',
      headers: [
        { title: 'ID', key: 'id', sortable: false },
        { title: 'Name', key: 'name' },
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
      return this.checkPermission('permision-delete');
    },

    ...mapState({
      message: state => state.permission.success_message,
      errors: state => state.permission.errors,
      success_status: state => state.permission.success_status,
      error_status: state => state.permission.error_status
    })
  },

  watch: {
    options: {
      handler () {
        this.getAllPermissions()
      },
      deep: true,
    },

    search: {
      handler () {
        this.getAllPermissions()
      },
    },
  },

  mounted() {
    this.getAllPermissions();
  },

  methods: {
    getAllPermissions(){
      this.loading = true

      const { sortBy, sortDesc, page, itemsPerPage } = this.options

      http().get('http://localhost:8000/api/v1/admin/permission', {
        params: {
          sortBy: sortBy[0],
          sortDesc: sortDesc,
          page: page,
          itemsPerPage: itemsPerPage,
          search: this.search
        }
      }).then((result) => {
        this.permissions = result.data.data.data;
        this.totalPermissions = result.data.data.total;
        this.loading = false;
      }).catch((err) => {
        console.log(err);
      })
    },

    calculateIndex(item) {
      return this.startIndex + item;
    },

    deletePermission: async function(id){
      try {
        await this.$store.dispatch("permission/DestroyPermission", id).then(() => {
          if (this.success_status === 200)
          {
            this.$swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: 'Permission destroy successful',
              showConfirmButton: false,
              timer: 1500
            });

            this.getAllPermissions();
          }
        })
      }catch (e) {
        this.$swal.fire({
          icon: 'error',
          text: 'Oops',
          title: 'Something wen wrong!!!',
        });
      }
    }
  }
}
</script>

<template>
  <div id="permission">
      <v-row class="mx-5">

        <v-col cols="12" class="pa-6">

          <v-row wrap>
            <v-col cols="6">
              <h1 :class="['text-subtitle-2', 'text-grey', 'mt-5']">Permission</h1>
            </v-col>
          </v-row>

          <v-row wrap>
            <v-col cols="12">
                <v-card elevation="8">
                  <v-row>
                    <v-col col="6">
                      <v-card-title :class="['text-subtitle-1']">All Permission Lists</v-card-title>
                    </v-col>

                    <v-col cols="6">
                      <v-card-actions class="justify-end">
                        <v-btn color="success" @click="navigateWithPermission('permision-create', '/add-permission')">
                          <v-icon small left>mdi-plus</v-icon>
                          <span>Add New</span>
                        </v-btn>
                      </v-card-actions>
                    </v-col>
                  </v-row>

                  <v-divider></v-divider>

                  <v-card-text>
                    <v-card-title class="d-flex align-center pe-2" style="justify-content: space-between">
                      <h1 :class="['text-subtitle-1', 'text-black']">Permission</h1>

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
                          :items="permissions"
                          :search="search"
                          v-model:options="options"
                          :items-length="totalPermissions"
                          :loading="loading"
                          item-value="name"
                          class="elevation-4"
                          fixed-header
                      >
                        <template v-slot:[`item.id`]="{ index }">
                          <td>{{ calculateIndex(index) }}</td>
                        </template>


                        <template v-slot:[`item.actions`]="{ item }">
                          <v-row align="center" justify="center">
                            <td :class="['mx-2']">
                                <v-btn @click="navigateWithPermission('permision-edit', `/edit-permission/${item.id}`)" color="warning" icon="mdi-pencil" size="x-small"></v-btn>
                            </td>

                            <td v-if="hasDeletePermission">
                                <v-btn color="red" icon="mdi-delete" size="x-small" @click="deletePermission(item.id)"></v-btn>
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