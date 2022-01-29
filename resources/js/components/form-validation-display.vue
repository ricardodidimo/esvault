<style scoped>
</style>

<template>
  <div class="w-100 d-flex flex-column align-items-center">
    <div class="w-100 my-2">
      <p class="alert alert-danger text-center" v-if="success === false">
        Oops! We could not proceed.
      </p>
      <p class="alert alert-success text-center" v-if="success">
        {{ successMessage }}
      </p>
    </div>

    <ul v-if="validationErrors.length > 0 && success === false">
      <li v-for="(validationError, index) in validationErrors" :key="index">
        {{ validationError }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    validationErrors: {
      type: Array,
      required: true,
    },
    success: {
      type: Boolean,
    },
  },
  computed: {
    successMessage: function () {
      return this.success ? this.validationErrors[0] : "";
    },
  },
};
</script>