<style scoped>
.a-link {
  font-size: 0.8rem;
}
.submit-btn-text {
  font-size: 1rem;
}

.form-body__container {
  padding: 1rem 5rem !important;
}

@media (max-width: 520px) {
  .form-body__container {
    padding: 1rem 3.5rem !important;
  }
}

@media (max-width: 450px) {
  .form-body__container {
    padding: 1rem 2rem !important;
  }
}
</style>

<template>
  <div>
    <div
      class="d-flex flex-column justify-content-center align-items-center pt-3"
    >
      <FormHead headText="Enter your account" class="mb-4"></FormHead>
      <FormBody
        v-on:submiting="loginUserSubmit"
        class="mb-3 form-body__container"
      >
        <div class="form-group mb-2">
          <label for="email">Email address</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            aria-describedby="emailHelp"
          />
        </div>
        <div class="form-group mb-2">
          <label for="password">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            autocomplete="on"
          />
          <AppValidation
            :validationErrorsField="validationErrors.login"
          ></AppValidation>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <AppPrimaryButton class="submit-btn-text me-5"
            >LogIn</AppPrimaryButton
          >
          <router-link :to="{ name: 'account-register' }" class="a-link"
            >Get an account! Register</router-link
          >
        </div>
      </FormBody>
    </div>
  </div>
</template>

<script>
import FormHead from "../../components/app-form-head.vue";
import FormBody from "../../components/app-form-body.vue";
import AppPrimaryButton from "../../components/app-primary-btn.vue";
import AppValidation from "../../components/app-validation.vue";

export default {
  components: {
    AppPrimaryButton,
    FormHead,
    FormBody,
    AppValidation,
  },
  data() {
    return {
      validationErrors: {
        login: [],
      },
    };
  },
  async mounted() {
    if ((await this.$store.dispatch("checkForGuest")) != true) {
      return;
    }
    this.$emit("component-ready");
  },
  methods: {
    populateValidationList: function (errorsListObject) {
      this.validationErrors = {
        login: errorsListObject?.login ?? [],
      };
    },
    loginUserSubmit: async function (form) {
      form.setToSubmitState();
      this.populateValidationList(null);
      const loginPayload = form.getFormData();

      try {
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.post("/api/account", loginPayload);
        if (response.status === 201) {
          this.$store.commit("deprecateUser");
          this.$router.push({ name: "credentials-index" });
        }
      } catch (e) {
        const response = e.response;
        if (response.status === 400) {
          const errorsListObject = response.data.data;
          this.populateValidationList(errorsListObject);
        }
      } finally {
        form.setToBeginState();
      }
    },
  },
};
</script>