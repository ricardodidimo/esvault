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

          <div class="mb-2 w-100 d-flex align-items-center vault-actions">
            <PaginationNavigation
              v-on:navigation-submit="getCredentials"
              :previousUrl="previousUrl"
              :nextUrl="nextUrl"
            >
            </PaginationNavigation>
            <div class="w-100 d-flex justify-content-end vault-actions-right-column align-items-end">
              <SearchBar v-on:search-submit="search" class="me-1"></SearchBar>
              <AddCredentialLink class="add-credential-container"></AddCredentialLink>
            </div>
          </div>

          <div
            class="w-100"
            v-for="credential in credentials"
            :key="credential.id"
          >
            <CredentialDisplay
              :id="credential.id"
              :title="credential.title"
              :description="credential.description"
              :first_claim="credential.first_claim"
              :second_claim="credential.second_claim"
            >
            </CredentialDisplay>
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
  },
  data() {
    return {
      loaded: false,
      credentials: [],
      previousUrl: null,
      nextUrl: null,
    };
  },
  async beforeMount() {
    const isAuth = await this.shouldBeAuthenticated();
    if (isAuth) {
      await this.getCredentials("/api/credentials");
      this.loaded = true;
    }
  },
  mixins: [formHelper, viewAuthorization, axiosHelper],
  methods: {
    populatePageData: function (resJsonData) {
      this.previousUrl = resJsonData.prev_page_url;
      this.nextUrl = resJsonData.next_page_url;
      this.credentials = resJsonData.data;
    },
    getCredentials: async function (credentialsUrl, subComponent = null) {
      if (subComponent != null) { subComponent.setRequestingState(); }

      await this.makeRequest(this.httpMethods.get, credentialsUrl, {
        handle200: {
          handlerFunc: this.handle200
        }
      });

      if (subComponent != null) { subComponent.setDoneState(); }
    },
    handle200: function (apiResponse) {
      const resJsonData = apiResponse.data.data;
      this.populatePageData(resJsonData);
    },
    search: async function (formObj, subComponent) {
      subComponent.setRequestingState();
      const titleForSearch = formObj.target.elements.title.value;
      const endpointUrl = `/api/credentials/${titleForSearch}`;
      await this.makeRequest(this.httpMethods.get, endpointUrl, {
        handle200: {
          handlerFunc: this.handle200
        }
      });
      subComponent.setDoneState();
    }
  },
};
</script>