<style scoped>
  textarea {
    resize: none;
  }
  .auth-action {
    color: white;
    text-decoration: none;
  }
  .auth-action:hover {
    color: var(--app-blue);
  }
  .auth-form {
    background-color: var(--app-black);
    width: 45%;
    border-radius: 1rem;
  }
  .lock {
    width: 90px;
    height: 90px;
  }
  .auth-input {
    display: block;
    width: 100%;
    background-color: var(--app-black);
    color: white;
    border: none;
    font-size: 1rem;
    border-bottom: 1px solid rgb(187, 175, 175);
  }
  .auth-btn {
    background-color: white;
    color: var(--app-black);
    width: 100%;
    font-weight: bold;
    border: none;
  }

  @media (max-width: 767px) {
    .auth-form {
      width: 75%;
    }
  }

  @media (max-width: 420px) {
    .auth-form {
      width: 100%;
    }
  }
</style>

<template>
  <div class="auth-form my-2 d-flex flex-column align-items-center">
    <img src="/images/lock.svg" alt="lock icon" class="my-2 lock" />
    <div class="w-75">
      <LoadingBar v-if="loading"></LoadingBar>
      <div class="d-flex justify-content-center">
        <ValidationErrors
          :validationErrors="validationErrors"
          :success="success"
        ></ValidationErrors>
      </div>
    </div>
    <form class="w-75" v-on:submit.prevent="formCallback">
      <slot></slot>
    </form>
  </div>
</template>

<script>
import LoadingBar from "./form-loading-bar.vue";
import ValidationErrors from "./form-validation-display.vue";

export default {
  components: {
    LoadingBar,
    ValidationErrors
  },
  data() {
      return {
        validationErrors: [],
        loading: false,
        success: null,
      }
  },
  methods: {
    formCallback: function (formObj) {
      this.$emit('form-submit', formObj, this);
    },
    setFormToRequestingState() {
      this.loading = true;
    },
    setFormToFeedbackState(success, messages) {
      this.loading = false;
      this.success = success;
      this.validationErrors = messages;
    },
  }
};
</script>