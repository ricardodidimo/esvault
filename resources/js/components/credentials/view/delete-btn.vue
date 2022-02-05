<style scoped>
.view__delete-btn {
  padding: 0.2rem 1.1rem;
  background-color: var(--app-black);
  border-radius: 0.5rem;
  cursor: pointer;
  color: white;
}
.view__delete_btn__confirm {
  padding: 0.3rem 1.5rem;
  background-color: var(--app-blue);
}
</style>

<template>
  <div class="d-flex flex-column justify-content-center align-items-center">
    <div
      class="
        view__delete-btn
        ms-1
        d-flex
        justify-content-center
        align-items-center
      "
      :class="{ view__delete_btn__confirm: confirmDelete }"
    >
      <img
        src="/images/delete-icon.svg"
        width="25"
        height="25"
        alt="delete credential icon"
        v-if="confirmDelete === false"
        v-on:click="deleteCredential"
      />
      <p v-else v-on:click="deleteCredential">Confirm</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    credentialTargetId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      confirmDelete: false,
    };
  },
  methods: {
    deleteCredential: async function () {
      if (this.confirmDelete === false) {
        this.confirmDelete = true;
        return;
      }

      try {
        this.$emit("needs-loading");
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.delete(
          "/api/credentials/" + this.credentialTargetId
        );
        if (response.status === 204) {
          this.$router.push({ name: "credentials-index" });
        }
      } catch (e) {
      }
    },
  },
};
</script>

