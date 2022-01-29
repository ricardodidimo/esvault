<style scoped>
button {
  margin-bottom: 0;
}

.panel {
  background-color: var(--app-black);
  width: 80%;
  border-radius: 0.5rem;
}

.panel-box {
  width: 90%;
}
.panel-hr {
  width: 100%;
  height: 2px;
  background-color: white;
}

@media(max-width: 767px) {
  .vault-actions {
    flex-direction: column;
    align-items: start !important;
  }
  .vault-actions-right-column{
    
    justify-content: start !important;
  }
}

</style>

<template>
<div>
  <LoadingPlaceholder v-if="loaded === false" class="loading-placeholder"></LoadingPlaceholder>
  <div v-if="loaded">
    <div class="d-flex flex-column align-items-center justify-content-center">
      <div class="panel my-2 d-flex flex-column align-items-center">
        <div
          class="
            panel-box
            d-flex
            flex-column
            justify-content-start
            align-items-center
            ps-2
          "
        >
          <PanelActions></PanelActions>

          <hr class="panel-hr" />

          <div class="mb-2 w-100 d-flex flex-column align-items-center">
            <button v-on:click="logOut" class="mb-3">LogOut</button>
            <AppForm v-on:form-submit="editProfile">
              <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" :value="userInfo.name" name="name" id="name">
              </div>
              <div class="mb-3">
                <label for="email">Email</label>
                <input type="text" :value="userInfo.email" name="email" id="email">
              </div>
              <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" :value="userInfo.password" id="password">
              </div>
              <div class="mb-3" v-if="askForPassword">
                <label for="current_password">Type your current password</label>
                <input type="password" name="current_password" id="current_password">
              </div>
              <button type="submit">Apply changes</button>
            </AppForm>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import CredentialDisplay from "../components/credentials/index/credential-display.vue";
import PanelActions from "../components/credentials/index/panel-actions.vue";
import SearchBar from "../components/credentials/index/search-bar.vue";
import PaginationNavigation from "../components/credentials/index/pagination-navigation.vue";
import AddCredentialLink from "../components/credentials/index/add-credential-link.vue";
import LoadingPlaceholder from "../components/app-loading-placeholder.vue";
import AppForm from "../components/app-form.vue";

import formHelper from "../mixins/formHelper";
import viewAuthorization from "../mixins/viewAuthorization";
import axiosHelper from '../mixins/axiosHelper';

export default {
  components: {
    LoadingPlaceholder,
    PanelActions,
    PaginationNavigation,
    SearchBar,
    AddCredentialLink,
    CredentialDisplay,
    AppForm
  },
  data() {
    return {
      loaded: false,
      askForPassword: false,
      userInfo: {}
    };
  },
  async beforeMount() {
      await this.makeRequest(
        this.httpMethods.get,
        "/api/account", 
        {
          handle200: {
            handlerFunc: this.handle200GetAccount
          },
          handle401: {
            handlerFunc: this.handle401GetAccount
          }
      });
  },
  mixins: [formHelper, viewAuthorization, axiosHelper],
  methods: {
    editProfile: async function(formObj, formComponent) {
      const formData = formObj.target.elements;
      if (formData.current_password === undefined) {
        this.askForPassword = true;
        return;
      }
      let formValues = this.getPayloadFromFormElements(formData);
      const payload = {
        current_password: formValues.current_password
      };

      formValues = Object.entries(formValues)
      for (let [key, value] of formValues) {
        console.log(key);
        if (this.userInfo[key] != value) {
          payload[key] = value;
        }
      }

      await this.makeRequest(
        this.httpMethods.put,
        "/api/users", 
        {
          payload: payload,
          handle204: {
            handlerFunc: this.handle204Update,
            args: [formComponent]
          },
          handle400: {
            handlerFunc: this.handle400Update,
            args: [formComponent]
          }
      });
    },
    handle204Update: function(apiResponse, appFormComponent) {
      appFormComponent.setFormToFeedbackState(true, ['Profile information updated.']);
    },
    handle400Update: function (apiResponse, appFormComponent) {
      let validationErrorsObject = apiResponse.data.data;
      validationErrorsObject = this.getValidationListFromResponse(
        validationErrorsObject
      );
      appFormComponent.setFormToFeedbackState(false, validationErrorsObject);
    },
    logOut: async function () {
      await this.makeRequest(
        this.httpMethods.delete,
        "/api/account", 
        {
          handle204: {
            handlerFunc: this.handle204LogOut
          }
      });
    },
    handle200GetAccount: function (apiResponse) {
      this.userInfo = apiResponse.data.data;
      this.userInfo.password = '****';
      this.loaded = true;
    },
    handle401GetAccount: function (apiResponse) {
      this.$router.push({ name: 'login' });
    },
    handle204LogOut: function (apiResponse) {
      this.$router.push({ name: "home"});
    }
  },
};
</script>