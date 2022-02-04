<style scoped>
</style>

<template>
  <div v-if="validationErrorsField.length > 0">

      <div class="d-flex justify-content-start align-items-center m-1" v-for="(error, index) in validationErrorsField" v-bind:key="index">
        <img src="/images/round-alert.svg" alt="alert for validation error icon" class="me-2"/>
        {{ error }}
      </div>
 
  </div>
</template>

<script>
export default {
  props: {
    validationErrorsField: {
      type: Array,
      required: true,
      default: [],
    }
  },
};
</script>