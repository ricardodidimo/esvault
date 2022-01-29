<style scoped>
</style>

<template>
  <div>
    <LoadingPlaceholder v-if="loaded === false" class="loading-placeholder">
    </LoadingPlaceholder>

    <div v-if="loaded">
      <div class="d-flex flex-column align-items-center justify-content-center">
        <navbar class="d-flex align-items-center">
          <router-link :to="{name: 'index-credentials'}" class="">
            <img src="/images/arrow-left.svg" alt="Go back to your credentials page" />
          </router-link>

          <h1 class="mx-2 my-0">Save your credential</h1>
        </navbar>

        <AppForm v-on:form-submit="createCredential">
          <div class="mb-3">
            <label for="title">Title for credential</label>
            <input
              type="text"
              placeholder="title"
              id="title"
              name="title"
              class="auth-input"
            />
          </div>
          <div class="mb-3">
            <label for="title">
              First claim <small>(such as email or name)</small>
            </label>
            <input
              type="text"
              placeholder="first claim"
              id="first_claim"
              name="first_claim"
              class="auth-input"
            />
          </div>
          <div class="mb-3">
            <label for="title">
              Second claim <small>(such as password)</small>
            </label>
            <input
              type="text"
              placeholder="second claim"
              id="second_claim"
              name="second_claim"
              class="auth-input"
            />
          </div>
          <div class="mb-3">
            <label for="description">Description</label>
            <textarea
              class="auth-input form-textarea"
              rows="3"
              name="description"
            ></textarea>
          </div>
          <button class="auth-btn py-2 mb-4" type="submit">Save</button>
        </AppForm>
      </div>
    </div>
  </div>
</template>

<script>
import AppForm from "../components/app-form.vue";
import LoadingPlaceholder from "../components/app-loading-placeholder.vue";

import formHelper from "../mixins/formHelper";
import viewAuthorization from "../mixins/viewAuthorization";
import axiosHelper from "../mixins/axiosHelper";

export default {
  components: {
    LoadingPlaceholder,
    AppForm,
  },
  data() {
    return {
      loaded: false,
    };
  },
  async beforeMount() {
    const isAuth = await this.shouldBeAuthenticated();
    this.loaded = isAuth;
  },
  mixins: [formHelper, viewAuthorization, axiosHelper],
  methods: {
    createCredential: async function (formObj, authFormComponent) {
      authFormComponent.setFormToRequestingState();
      const payload = this.getPayloadFromFormElements(formObj.target.elements);
      this.makeRequest(this.httpMethods.post, "/api/credentials", {
        payload: payload,
        handle201: {
          handlerFunc: this.handle201,
          args: [authFormComponent],
        },
        handle400: {
          handlerFunc: this.handle400,
          args: [authFormComponent],
        },
      });
    },
    handle201: function (response, authFormComponent) {
      authFormComponent.setFormToFeedbackState(true, [
        "Credential successfully saved!",
      ]);
    },
    handle400: function (response, authFormComponent) {
      let validationErrorsObject = response.data.data;
      validationErrorsObject = this.getValidationListFromResponse(
        validationErrorsObject
      );
      authFormComponent.setFormToFeedbackState(false, validationErrorsObject);
    },
  },
};
</script>