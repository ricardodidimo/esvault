<style scoped>
.verified__container {
  width: 80vw;
  height: 80vh;
}
</style>

<template>
  <div
    class="
      verified__container
      d-flex
      flex-column
      justify-content-center
      align-items-center
    "
  >
    <h1>Email verified!</h1>
    <AppBtn v-on:click.native="returnHome">Ok</AppBtn>
  </div>
</template>

<script>
import AppBtn from "../../components/app-primary-btn.vue";
export default {
  components: {
    AppBtn,
  },
  async mounted() {
    this.$store.commit("deprecateUser");
    if (
      (await this.$store.dispatch("checkForAuthenticated")) != true ||
      (await this.$store.dispatch("checkForVerifiedUser")) != true
    ) {
      return;
    }
    this.$emit("component-ready");
  },
  methods: {
    returnHome: function () {
      this.$router.push({ name: "credentials-index" });
    },
  }
};
</script>
