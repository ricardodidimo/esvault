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
      <FormHead headText="Create an account" class="mb-4"></FormHead>
      <FormBody
        v-on:submiting="registerUserSubmit"
        class="mb-3 form-body__container"
      >
        <div class="form-group mb-2">
          <label for="name">Name</label>
          <input type="name" class="form-control" id="name" name="name" />
          <AppValidation
            :validationErrorsField="validationErrors.name"
          ></AppValidation>
        </div>
        <div class="form-group mb-2">
          <label for="email">Email address</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            aria-describedby="emailHelp"
          />
          <small id="emailHelp" class="form-text text-muted"
            >Enter a valid email which you have access</small
          >
          <AppValidation
            :validationErrorsField="validationErrors.email"
          ></AppValidation>
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
            :validationErrorsField="validationErrors.password"
          ></AppValidation>
        </div>
        <div class="form-group mb-2">
          <label for="password_confirmation">Repeat password</label>
          <input
            type="password"
            class="form-control"
            id="password_confirmation"
            name="password_confirmation"
            autocomplete="on"
          />
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <AppPrimaryButton class="submit-btn-text me-5"
            >Register</AppPrimaryButton
          >
          <router-link :to="{ name: 'account-login' }" class="a-link"
            >Has an account? LogIn</router-link
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
        name: [],
        email: [],
        password: [],
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
        name: errorsListObject?.name ?? [],
        email: errorsListObject?.email ?? [],
        password: errorsListObject?.password ?? [],
      };
    },

    registerUserSubmit: async function (form) {
      form.setToSubmitState();
      this.populateValidationList(null);
      const registerPayload = form.getFormData();

      try {
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.post("/api/users", registerPayload);
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