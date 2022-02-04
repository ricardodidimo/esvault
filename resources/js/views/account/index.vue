<style scoped>
h1 {
  margin: 0;
}
.account__bar {
  padding: 1rem 0;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0px 3px 3px var(--app-black);
}

.form-body__container {
  padding: 1rem 4rem !important;
}
@media (max-width: 640px) {
  .account__form-container {
    flex-direction: column;
  }
  .form-body__container {
    padding: 1rem 3rem !important;
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
      class="d-flex flex-column justify-content-center align-items-center pt-3"
    >
      <div
        class="
          account__bar
          d-flex
          justify-content-between
          align-items-center
          w-100
          mb-5
        "
      >
        <h1 class="fs-5 px-5">Your profile</h1>
        <AccountLogout v-on:needs-loading="$emit('needs-loading')"></AccountLogout>
      </div>
      <div class="d-flex account__form-container">
        <AccountUpdate
          class="form-body__container"
          ref="updateForm"
        ></AccountUpdate>

        <FormBody class="mb-3 form-body__container">
          <div
            class="
              d-flex
              flex-column
              justify-content-between
              align-items-center
            "
          >
            <img src="/images/profile-icon.svg" alt="profile ilustrative icon" />
            <AppPrimaryButton
              class="mt-2"
              color="var(--app-blue)"
              v-on:click.native="goDeleteConfirmationPage"
              >Delete account</AppPrimaryButton
            >
          </div>
        </FormBody>
      </div>
    </div>
  </div>
</template>

<script>
import FormHead from "../../components/app-form-head.vue";
import FormBody from "../../components/app-form-body.vue";
import AppPrimaryButton from "../../components/app-primary-btn.vue";
import AccountUpdate from "../../components/account/index/account-update.vue";
import AccountLogout from "../../components/account/index/account-logout.vue";

export default {
  components: {
    AppPrimaryButton,
    FormHead,
    FormBody,
    AccountUpdate,
    AccountLogout
  },
    async mounted() {
    if ((await this.$store.dispatch("checkForAuthenticated")) != true) {
      return;
    }

    this.populateFormWithAccountInfo();
    this.$emit("component-ready");
  },
  methods: {
    populateFormWithAccountInfo: function () {
      const updateChildren = this.$refs.updateForm;
      const user = this.$store.state.user;
      const initialUserInfo = {
        name: user.userData.name,
        email: user.userData.email,
      };
      updateChildren.updateUserInfo(initialUserInfo);
    },
    goDeleteConfirmationPage: async function () {
      this.$router.push({ name: "account-confirm" });
    },
  },

};
</script>