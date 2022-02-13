<style scoped>
.a-link {
  font-size: 0.8rem;
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
  <FormBody  headerText="Edit credential" v-on:submiting="updateCredentialSubmit" class="mb-3 form-body__container">
    <div class="form-group mb-2">
      <label for="title">Title</label>
      <input
        type="text"
        class="form-control"
        id="title"
        name="title"
        aria-describedby="title"
        v-model="credential.title"
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
        v-model="credential.first_claim"
      />
      <AppValidation
        :validationErrorsField="validationErrors.first_claim"
      ></AppValidation>
    </div>
    <div class="form-group mb-2">
      <label for="second_claim">Second claim</label>
      <input
        class="form-control"
        id="second_claim"
        name="second_claim"
        aria-describedby="second_claim"
        v-model="credential.second_claim"
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
        v-model="credential.description"
      ></textarea>
      <AppValidation
        :validationErrorsField="validationErrors.description"
      ></AppValidation>
    </div>
    <div class="d-flex justify-content-between align-items-center">
      <router-link :to="{ name: 'credentials-index' }">
        <img src="/images/back-arrow.svg" alt="back to previous page icon" />
      </router-link>
      <div class="ms-5 d-flex align-items-center">
        <AppPrimaryButton class="submit-btn-text">Update</AppPrimaryButton>
        <CredentialDeleteButton
          :credentialTargetId="credential.id"
          v-on:needs-loading="$emit('needs-loading')"
        ></CredentialDeleteButton>
      </div>
    </div>
  </FormBody>
</template>

<script>
import FormBody from "../../app-form-body.vue";
import AppPrimaryButton from "../../app-primary-btn.vue";
import AppValidation from "../../app-validation.vue";
import CredentialDeleteButton from "./delete-btn.vue";
export default {
  components: {
    AppPrimaryButton,
    FormBody,
    AppValidation,
    CredentialDeleteButton,
  },
  data() {
    return {
      validationErrors: {
        title: [],
        description: [],
        first_claim: [],
        second_claim: [],
      },
      credential: {
        id: 0,
        title: "",
        description: "",
        first_claim: "",
        second_claim: "",
      },
    };
  },
  methods: {
    populateCredentialInfo: function (credentialData) {
      this.credential = {
        id: credentialData.id,
        title: credentialData.title,
        description: credentialData.description,
        first_claim: credentialData.first_claim,
        second_claim: credentialData.second_claim,
      };
    },
    populateValidationList: function (errorsListObject) {
      this.validationErrors = {
        title: errorsListObject?.title ?? [],
        description: errorsListObject?.description ?? [],
        first_claim: errorsListObject?.first_claim ?? [],
        second_claim: errorsListObject?.second_claim ?? [],
      };
    },
    prepareFormForRequest: function (formComponent) {
      formComponent.setToSubmitState();
      this.populateValidationList(null);
      formComponent.setSuccessfulSubmitMessage(null);
    },
    updateCredentialSubmit: async function (form) {
      this.prepareFormForRequest(form);
      const updateCredentialPayload = form.getFormData();

      try {
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.put(
          "/api/credentials/" + this.credential.id,
          updateCredentialPayload
        );
        if (response.status === 204) {
          form.setSuccessfulSubmitMessage(
            "Credential was successfully updated."
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

