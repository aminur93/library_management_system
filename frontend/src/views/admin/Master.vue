<script>
import LibraryLayout from "@/components/admin/Layout.vue";

export default {
  name: "LibraryMaster",
  components: {LibraryLayout}
}
</script>

<template>
  <v-app>

    <LibraryLayout/>

    <v-main>
      <router-view></router-view>
    </v-main>

  </v-app>
</template>

<style scoped>

</style>