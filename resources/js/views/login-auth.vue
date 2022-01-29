<style scoped>
.auth-action {
  color: white;
  text-decoration: none;
}
</style>

<template>
  <div style="overflow: hidden;">
    <LoadingPlaceholder
      v-if="loaded === false"
      class="loading-placeholder"
    ></LoadingPlaceholder>

    <div v-if="loaded">
      <navbar class="d-flex justify-content-center">
        <router-link :to="{name: 'register'}" class="auth-action fs-3 px-5"
          >Register</router-link
        >
        <router-link :to="{name: 'login'}" class="auth-action fs-3 px-5 active-link"
          >Login</router-link
        >
      </navbar>
      <div class="d-flex justify-content-center">
        <AppForm v-on:form-submit="authenticate">
          <div class="mb-3">
            <label for="email">Email</label>
            <input
              type="text"
              placeholder="email"
              id="email"
              name="email"
              class="auth-input"
            />
          </div>
          <div class="mb-3">
            <label for="password">Password</label>
            <input
              type="text"
              placeholder="Password"
              id="password"
              name="password"
              class="auth-input"
            />
          </div>
          <button class="auth-btn py-2 mb-5" type="submit">Login</button>
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
  async beforeMount() {
    const isGuest = await this.shouldBeGuest();
    this.loaded = isGuest;
  },
  data() {
    return {
      loaded: false,
    };
  },
  mixins: [formHelper, viewAuthorization, axiosHelper],
  methods: {
    authenticate: async function (formObj, authFormComponent) {
      authFormComponent.setFormToRequestingState();
      const payload = this.getPayloadFromFormElements(formObj.target.elements);

      this.makeRequest(this.httpMethods.post, "/api/account", {
        payload: payload,
        handle201: {
          handlerFunc: this.handle201,
        },
        handle400: {
          handlerFunc: this.handle400,
          args: [authFormComponent],
        },
      });
    },
    handle201: function (response) {
      this.$router.push({ name: "index-credentials" });
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