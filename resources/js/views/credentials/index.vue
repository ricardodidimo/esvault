<style scoped>
.add-btn {
  background-color: black;
  color: white;
  padding: 0rem 1rem;
  border-radius: 0.5rem;
}
</style>

<template>
  <div>
    <div
      class="
        d-flex
        flex-column
        justify-content-center
        align-items-center
        py-5
      "
    >
      <div class="d-flex justify-content-start w-100">
        <SearchBox v-on:requireSearch="getCredentials"></SearchBox>
        <router-link
          :to="{ name: 'credentials-create' }"
          class="add-btn d-flex justify-content-center align-items-center px-2"
        >
          <img width="22px" height="22px" src="/images/add-icon.svg" alt="add new credential icon" />
        </router-link>
      </div>

      <CredentialsList
        :credentials="credentials"
        :previousPage="previousPage"
        :nextPage="nextPage"
        v-on:getPrevious="getCredentials(previousPage)"
        v-on:getNext="getCredentials(nextPage)"
      ></CredentialsList>
    </div>
  </div>
</template>

<script>
import AppCard from "../../components/app-card.vue";
import AppPrimaryButton from "../../components/app-primary-btn.vue";
import CredentialsList from "../../components/credentials/index/credentials-list.vue";
import SearchBox from "../../components/credentials/index/search-box.vue";

export default {
  components: {
    AppCard,
    AppPrimaryButton,
    CredentialsList,
    SearchBox,
  },
  data() {
    return {
      credentials: [],
      previousPage: null,
      nextPage: null,
    };
  },
  async mounted() {
    if (
      (await this.$store.dispatch("checkForAuthenticated")) != true ||
      (await this.$store.dispatch("checkForVerifiedUser")) != true
    ) {
      return;
    }

    await this.getCredentials();
    this.$emit("component-ready");
  },
  methods: {
    getCredentials: async function (actionUrl = "/api/credentials") {
      try {
        const response = await axios.get(actionUrl);
        if (response.status === 200) {
          this.credentials = response.data.data.data;
          this.previousPage = response.data.data.prev_page_url;
          this.nextPage = response.data.data.next_page_url;
        }
      } catch (e) {}
    },
  },
};
</script>