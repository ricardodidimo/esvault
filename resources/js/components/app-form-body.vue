<style scoped>
.form-body__container {
  background-color: white;
  display: inline-block !important;
  box-shadow: 0px 3px 3px var(--app-black);
  border-radius: 0rem 0rem 0.5rem 0.5rem;
}
.form-body__header > h1 {
  margin: 0;
}

input,
textarea {
  background-color: white;
  border: 1px solid var(--app-black);
  border-radius: 0.5rem;
}
textarea {
  resize: none;
}
input:focus,
textarea:focus {
  border: 1px solid var(--app-blue);
  box-shadow: 0px 0px 7px var(--app-blue);
}
</style>

<template>
  <div
    class="
      form-body__container
      d-flex
      flex-column
      align-items-center
      justify-content-center
    "
  >
    <div
      class="d-flex justify-content-center align-items-center form-body__header"
    >
      <img
        class="img-fluid"
        width="25px"
        height="25px"
        src="/images/lock-icon.svg"
        alt="ilustrative form head icon"
      />
      <h1 class="ms-1 fs-4 text-center">{{headerText}}</h1>
    </div>

    <hr />
    <div
      class="
        alert alert-success
        d-flex
        justify-content-center
        align-items-center
      "
      v-if="successfulSubmit != null"
    >
      {{ successfulSubmit }}
    </div>
    <LoadingPlaceholder v-if="isSubmiting" class="py-2"></LoadingPlaceholder>
    <form v-on:submit.prevent="submitForm">
      <slot></slot>
    </form>
  </div>
</template>
<script>
import PrimaryButton from "../components/app-primary-btn.vue";
import LoadingPlaceholder from "../components/app-loading-placeholder.vue";
import AppValidation from "../components/app-validation.vue";

export default {
  components: {
    PrimaryButton,
    LoadingPlaceholder,
  },
  props: {
    headerText: {
      type: String,
      default: "Form headline.",
    },
  },
  data() {
    return {
      isSubmiting: false,
      successfulSubmit: null,
      validationErrors: {},
    };
  },
  methods: {
    setToSubmitState() {
      this.isSubmiting = true;
    },
    setToBeginState() {
      this.isSubmiting = false;
    },
    setSuccessfulSubmitMessage(value) {
      this.successfulSubmit = value;
    },
    getFormElement() {
      return this.formElement;
    },
    getFormData() {
      const data = {};
      const dataSources = Array.from(this.formElement.target.elements);
      dataSources.forEach((element) => {
        data[element.name] = element.value;
      });

      return data;
    },
    submitForm: function (formElement) {
      this.formElement = formElement;
      this.$emit("submiting", this);
    },
  },
};
</script>