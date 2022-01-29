<style scoped>
.btn-delete {
  background-color: var(--app-orange);
  font-weight: bold;
}

.delete-block {
  background-color: white;
  width: 20%;
  cursor: pointer;
}

.confirm-delete-block {
  background-color: var(--app-orange);
  line-height: 0.8rem;
}

.delete-action {
  width: 35%;
  height: auto;
  margin-bottom: 0.2rem;
}

@media (max-width: 768px) {
  .delete-block {
    width: 25%;
  }
}
</style>

<template>
  <div>
    <LoadingPlaceholder
      v-if="loaded === false"
      class="loading-placeholder"
    ></LoadingPlaceholder>

    <div v-if="loaded">
      <div class="d-flex flex-column align-items-center justify-content-center">
        <navbar class="d-flex align-items-center justify-content-center">
          <router-link :to="{name: 'index-credentials'}" class="">
            <img src="/images/arrow-left.svg" alt="Go back to your credentials page" />
          </router-link>

          <h1 class="mx-2 mb-0">Edit credential</h1>
        </navbar>

        <AppForm v-on:form-submit="updateCredential">
          <div class="mb-3">
            <label for="title">Title for credential</label>
            <input
              type="text"
              placeholder="title"
              id="title"
              name="title"
              class="auth-input"
              :value="title"
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
              :value="first_claim"
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
              :value="second_claim"
            />
          </div>
          <div class="mb-3">
            <label for="description">Description</label>
            <textarea
              class="auth-input form-textarea"
              rows="3"
              name="description"
              :value="description"
            ></textarea>
          </div>
          <div class="d-flex mb-2">
            <button class="auth-btn py-2 mb-2" type="submit">Update</button>
            <div
              class="
                delete-block
                d-flex
                align-items-center
                justify-content-center
                mb-2
                ms-1
              "
              v-if="confirmDelete === false"
              v-on:click="triggerDeleteConfirmation"
            >
              <img
                class="delete-action"
                src="/images/confirm-delete-icon.svg"
                title="delete credential"
                alt="Icon for delete credential"
              />
            </div>

            <div
              class="
                text-center
                delete-block
                confirm-delete-block
                d-flex
                align-items-center
                justify-content-center
                mb-2
                ms-1
              "
              v-if="confirmDelete"
              v-on:click="deleteCredential"
            >
              <small>Confirm</small>
            </div>
          </div>
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
      confirmDelete: false,
      id: "",
      title: "",
      description: "",
      first_claim: "",
      second_claim: "",
    };
  },
  async beforeMount() {
    const isAuth = await this.shouldBeAuthenticated();
    if (isAuth) {
      let targetCredential = localStorage.getItem("credential-info");
      if (targetCredential === null) {
        this.$router.push({ name: "index-credentials" });
        return;
      }
      targetCredential = JSON.parse(targetCredential);
      this.setCredentialData(targetCredential);
      this.loaded = true;
    }
  },
  destroyed() {
    localStorage.removeItem("credential-info");
  },
  mixins: [formHelper, viewAuthorization, axiosHelper],
  methods: {
    setCredentialData(data) {
      this.id = data.id;
      this.title = data.title;
      this.description = data.description;
      this.first_claim = data.first_claim;
      this.second_claim = data.second_claim;
    },
    updateCredential: async function (formObj, authFormComponent) {
      authFormComponent.setFormToRequestingState();
      const payload = this.getPayloadFromFormElements(formObj.target.elements);
      const endpointUrl = `/api/credentials/${this.id}`;

      await this.makeRequest(this.httpMethods.put, endpointUrl, {
        payload: payload,
        handle204: {
          handlerFunc: this.handle204Put,
          args: [payload, authFormComponent]
        },
        handle400: {
          handlerFunc: this.handle400Put,
          args: [authFormComponent]
        }
      });
    },
    handle204Put: function (apiResponse, payload, authFormComponent) {
      payload.id = this.id;
      localStorage.setItem("credential-info", JSON.stringify(payload));
      authFormComponent.setFormToFeedbackState(true, [
        "Credential successfully updated!",
      ]);
    },
    handle400Put: function (apiResponse, authFormComponent) {
          let validationErrorsObject = apiResponse.data.data;
          validationErrorsObject = this.getValidationListFromResponse(
            validationErrorsObject
          );
          authFormComponent.setFormToFeedbackState(
            false,
            validationErrorsObject
          );
    },
    triggerDeleteConfirmation: async function () {
      this.confirmDelete = true;
    },
    deleteCredential: async function () {
      this.loaded = false;
      const endpointUrl = `/api/credentials/${this.id}`;

      await this.makeRequest(this.httpMethods.delete, endpointUrl, {
        handle204: {
          handlerFunc: this.handle204Delete
        }
      });
    },
    handle204Delete: function (response) {
      this.$router.push({ name: "index-credentials" });
    },
  },
};
</script>