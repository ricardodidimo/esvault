<style scoped>
</style>

<template>
  <div>
    <div
      class="d-flex flex-column justify-content-center align-items-center pt-2"
    >
      <FormHead headText="Edit credential" class="ms-2 mb-3"></FormHead>
      <CredentialViewForm v-on:needs-loading="$emit('needs-loading')"></CredentialViewForm>
    </div>
  </div>
</template>

<script>
import FormHead from "../../components/app-form-head.vue";
import CredentialViewForm from "../../components/credentials/view/view-form.vue";

export default {
  components: {
    CredentialViewForm,
    FormHead,
  },
  async mounted() {
    if (
      (await this.$store.dispatch("checkForAuthenticated")) != true ||
      (await this.$store.dispatch("checkForVerifiedUser")) != true
    ) {
      return;
    }

    const credentialId = this.$router.currentRoute.params.credentialId;
    await this.getCredentialInfo(credentialId);
    this.$emit("component-ready");
  },
  methods: {
    verifySearchForInexistingCredential: function (errorResponse) {
        if (errorResponse.status === 400) {
          this.$router.push({ name: "credentials-index" });
        }
    },
    getCredentialInfo: async function (credentialId) {
      try {
        const response = await axios.get("/api/credentials/" + credentialId);
        if (response.status === 200) {
          const retrievedData = response.data.data;
          const viewFormComponent = this.$children[1];
          viewFormComponent.populateCredentialInfo(retrievedData);
        }
      } catch (e) {
        const response = e.response;
        this.verifySearchForInexistingCredential(response);
      }
    },
  },
};
</script>