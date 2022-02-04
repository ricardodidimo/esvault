<style scoped>

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
      class="d-flex flex-column justify-content-center align-items-center pt-2"
    >
      <FormHead headText="Create a credential" class="ms-2 mb-3"></FormHead>

      <FormBody v-on:submiting="createCredentialSubmit" class="mb-3" id="formBody">
        <div class="form-group mb-2">
          <label for="title">Title</label>
          <input
            type="text"
            class="form-control"
            id="title"
            name="title"
            aria-describedby="title"
          />
          <AppValidation
            :validationErrorsField="validationErrors.title"
          ></AppValidation>
        </div>
        <div class="form-group mb-2">
          <label for="first_claim">First claim</label>
          <input
            type="text"
            class="form-control"
            id="first_claim"
            name="first_claim"
            aria-describedby="first_claim"
            autocomplete="on"
          />
          <AppValidation
            :validationErrorsField="validationErrors.first_claim"
          ></AppValidation>
        </div>
        <div class="form-group mb-2">
          <label for="second_claim">Second claim</label>
          <input
            type="password"
            class="form-control"
            id="second_claim"
            name="second_claim"
            aria-describedby="second_claim"
            autocomplete="on"
          />
          <AppValidation
            :validationErrorsField="validationErrors.second_claim"
          ></AppValidation>
        </div>
        <div class="form-group mb-2">
          <label for="description">Description</label>
          <textarea
            class="form-control"
            name="description"
            id="description"
            cols="40"
            rows="6"
          ></textarea>
          <AppValidation
            :validationErrorsField="validationErrors.description"
          ></AppValidation>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <router-link :to="{ name: 'credentials-index' }">
            <img src="/images/back-arrow.svg" alt="back to previous page icon" />
          </router-link>
          <AppPrimaryButton class="submit-btn-text ms-5"
            >Create</AppPrimaryButton
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
        title: [],
        description: [],
        first_claim: [],
        second_claim: [],
      },
    };
  },
  async mounted() {
    if (
      (await this.$store.dispatch("checkForAuthenticated")) != true ||
      (await this.$store.dispatch("checkForVerifiedUser")) != true
    ) {
      return;
    }

    this.$emit("component-ready");
  },
  methods: {
    populateValidationList: function (errorsListObject) {
      this.validationErrors = {
        title: errorsListObject?.title ?? [],
        description: errorsListObject?.description ?? [],
        first_claim: errorsListObject?.first_claim ?? [],
        second_claim: errorsListObject?.second_claim ?? [],
      };
    },
    initializeCreateForm: function (form) {
      form.setToSubmitState();
      this.populateValidationList();
      form.setSuccessfulSubmitMessage(null);
    },
    createCredentialSubmit: async function (form) {
      this.initializeCreateForm(form);
      const createCredentialPayload = form.getFormData();

      try {
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.post(
          "/api/credentials",
          createCredentialPayload
        );
        if (response.status === 201) {
          form.setSuccessfulSubmitMessage(
            "Credential was successfully created."
          );
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