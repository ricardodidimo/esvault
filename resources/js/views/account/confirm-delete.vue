<style scoped>
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
      class="d-flex flex-column justify-content-center align-items-center py-3"
    >
      <AppCard cardTitle="Delete" class="mb-3">
        {{ headText }}
      </AppCard>
      <AppForm class="form-body__container" v-on:submiting="deleteAccountSubmit">
        <div class="form-group mb-2">
          <label for="current_password">Type your current password</label>
          <input
            type="password"
            class="form-control"
            id="current_password"
            name="current_password"
            autocomplete="on"
          />
          <AppValidation
            :validationErrorsField="validationErrors.current_password"
          ></AppValidation>
        </div>
        <AppPrimaryButton>Confirm delete</AppPrimaryButton>
      </AppForm>
    </div>
  </div>
</template>

<script>
import AppCard from "../../components/app-card.vue";
import AppPrimaryButton from "../../components/app-primary-btn.vue";
import AppForm from "../../components/app-form-body.vue";
import AppFormHead from "../../components/app-form-head.vue";
import AppValidation from "../../components/app-validation.vue";

export default {
  components: {
    AppCard,
    AppPrimaryButton,
    AppFormHead,
    AppForm,
    AppValidation,
  },
  async mounted() {
    if ((await this.$store.dispatch("checkForAuthenticated")) != true) {
      return;
    }

    this.$emit("component-ready");
  },
  data() {
    return {
      validationErrors: {
        current_password: [],
      },
      headText:
        "Are you sure about this? Confirm action by typing you current password below.",
    };
  },
  methods: {
    confirmUserPassword: async function (formComponent) {
      formComponent.setToSubmitState();
      const confirmPayload = formComponent.getFormData();
      try {
        await axios.get("/sanctum/csrf-cookie");
        const confirmResponse = await axios.post("/api/account/confirmation", confirmPayload);
        if (confirmResponse.status === 204) {
          return true;
        }
      } catch (e) {
        const confirmResponse = e.response;
        if (confirmResponse.status === 400) {
          const validationErrorsObject = confirmResponse.data.data;
          this.validationErrors.current_password =
            validationErrorsObject.current_password;
        }
        formComponent.setToBeginState();
        return false;
      }
    },
    deleteAccountSubmit: async function (form) {
      if (await this.confirmUserPassword(form) === false) {
        return;
      }

      this.$emit("needs-loading");
      try {
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.delete("/api/users");
        if (response.status === 204) {
          this.$store.commit("deprecateUser");
          this.$router.push({ name: "home" });
        }
      } catch (e) {}
    },
  },
};
</script>