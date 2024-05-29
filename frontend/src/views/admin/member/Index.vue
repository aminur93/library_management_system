<script>
import {mapState} from "vuex";
import {http} from "@/service/HttpService";
import permissionMixins from "@/mixins/PermissionMixins";

export default {
  name: "MemberIndex",
  mixins: [permissionMixins],

  data(){
    return{
      totalMembers: 0,
      members: [],
      loading: true,
      options: {},
      search: '',
      headers: [
        { title: 'ID', key: 'id', sortable: false },
        { title: 'First Name', key: 'first_name' },
        { title: 'Last Name', key: 'last_name' },
        { title: 'Email', key: 'email' },
        { title: 'Phone Number', key: 'phone_number' },
        { title: 'Registration Date', key: 'registration_date' },
        { title: 'Actions', key: 'actions', align: 'center', sortable: false },
      ]
    }
  },

  computed: {
    startIndex() {
      let currentPage = this.options.page;
      let itemsPerPage = this.options.itemsPerPage;

      return (currentPage - 1) * itemsPerPage + 1;
    },

    hasDeletePermission() {
      return this.checkPermission('member-delete');
    },

    ...mapState({
      message: state => state.member.success_message,
      errors: state => state.member.errors,
      success_status: state => state.member.success_status,
      error_status: state => state.member.error_status
    })
  },

  watch: {
    options: {
      handler () {
        this.getAllMember()
      },
      deep: true,
    },

    search: {
      handler () {
        this.getAllMember()
      },
    },
  },

  mounted() {
    this.getAllMember();
  },

  methods: {
    getAllMember(){
      this.loading = true

      const { sortBy, sortDesc, page, itemsPerPage } = this.options

      http().get('http://localhost:8000/api/v1/admin/member', {
        params: {
          sortBy: sortBy[0],
          sortDesc: sortDesc,
          page: page,
          itemsPerPage: itemsPerPage,
          search: this.search
        }
      }).then((result) => {
        this.members = result.data.data.data;
        this.totalMembers = result.data.data.total;
        this.loading = false;
      }).catch((err) => {
        console.log(err);
      })
    },

    calculateIndex(item) {
      return this.startIndex + item;
    },

    deleteMember: async function(id){
      try {
        await this.$store.dispatch("member/DeleteMember", id).then(() => {
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

            this.getAllMember();
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
            <h1 :class="['text-subtitle-2', 'text-grey', 'mt-5']">Member</h1>
          </v-col>
        </v-row>

        <v-row wrap>
          <v-col cols="12">
            <v-card elevation="8">
              <v-row>
                <v-col col="6">
                  <v-card-title :class="['text-subtitle-1']">All Member Lists</v-card-title>
                </v-col>

                <v-col cols="6">
                  <v-card-actions class="justify-end">
                    <v-btn color="success" @click="navigateWithPermission('member-create', '/add-member')">
                      <v-icon small left>mdi-plus</v-icon>
                      <span>Add New</span>
                    </v-btn>
                  </v-card-actions>
                </v-col>
              </v-row>

              <v-divider></v-divider>

              <v-card-text>
                <v-card-title class="d-flex align-center pe-2" style="justify-content: space-between">
                  <h1 :class="['text-subtitle-1', 'text-black']">Member</h1>

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
                    :items="members"
                    :search="search"
                    v-model:options="options"
                    :items-length="totalMembers"
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
                        <v-btn @click="navigateWithPermission('member-edit', `/edit-member/${item.id}`)" color="warning" icon="mdi-pencil" size="x-small"></v-btn>
                      </td>

                      <td v-if="hasDeletePermission">
                        <v-btn color="red" icon="mdi-delete" size="x-small" @click="deleteMember(item.id)"></v-btn>
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