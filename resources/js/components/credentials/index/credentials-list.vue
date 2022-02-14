<style scoped>
thead {
  background-color: white;
  box-shadow: 0px 3px 3px var(--app-black);
  border-radius: 0.5rem;
}
tbody {
  border-top: 1px solid !important;
  border-color: var(--app-black) !important;
}
td {
  width: 20%;
}
tr {
  text-align: center;
  vertical-align: middle;
}
.list__navigation {
  opacity: 0.5;
  cursor: initial;
  text-decoration: none;
}
.list__navigation-container {
  color: var(--app-blue);
}
.list__navigation--active {
  opacity: 1;
  cursor: pointer;
  text-decoration: underline;
}
</style>
<template>
  <div class="w-100">
    <div class="d-flex list__navigation-container">
      <p
        v-bind:class="{ 'list__navigation--active': previousPage }"
        v-on:click="navigate('getPrevious')"
        class="px-2 list__navigation"
      >
        previous
      </p>
      <p class="mx-2">|</p>
      <p
        v-bind:class="{ 'list__navigation--active': nextPage }"
        v-on:click="navigate('getNext')"
        class="list__navigation"
      >
        next
      </p>
      <AppLoading class="search__loading mx-2 mt-1" v-if="fetching"></AppLoading>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr class="mb-2">
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">First claim</th>
            <th scope="col">Second claim</th>
          </tr>
        </thead>
        <div v-if="credentials.length === 0" class="p-3 d-flex">
          <small class="fs-5">No credentials...</small>
        </div>
        <tbody v-for="credential in credentials" :key="credential.id">
          <CredentialRow :credential="credential"></CredentialRow>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import CredentialRow from "./credential-row.vue";
import AppLoading from "../../app-loading-placeholder.vue";

export default {
  components: {
    CredentialRow,
    AppLoading,
  },
  data() {
    return {
      fetching: false,
    };
  },
  methods: {
    navigate: function (action) {
      const url = action === "getPrevious" ? this.previousPage : this.nextPage;
      this.$emit(action, this, url);
    },
    setToFetchingState: function () {
      this.fetching = true;
    },
    setToInitialState: function () {
      this.fetching = false;
    },
  },
  props: {
    credentials: {
      type: Array,
      default: [],
    },
    previousPage: {
      type: String | null,
      default: null,
    },
    nextPage: {
      type: String | null,
      default: null,
    },
  },
};
</script>
