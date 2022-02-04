<style>
</style>

<template>
  <AppBtn v-on:click.native="logoutAccountSubmit" class="me-5">LogOut</AppBtn>
</template>

<script>
import AppBtn from "../../../components/app-primary-btn.vue";
export default {
  components: {
    AppBtn,
  },
  methods: {
    logoutAccountSubmit: async function () {
      this.$emit("needs-loading");
      try {
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.delete("/api/account");
        if (response.status === 204) {
          this.$store.commit("deprecateUser");
          this.$router.push({ name: "home" });
        }
      } catch (e) {}
    },
  },
};
</script>