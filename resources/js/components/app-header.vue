<style scoped>
.header__overflow-fix {
  overflow: hidden;
  box-shadow: 0px 1px 1px var(--app-black);
}
.header__container {
  width: 100vw;
  background-color: white;
}

@media (max-width: 404px) {
  .header__app-logo {
    display: none;
  }
  .header__container-content {
    justify-content: center !important;
  }
}
</style>

<template>
  <div class="header__overflow-fix">
    <div
      class="
        header__container
        d-flex
        justify-content-center
        align-items-center
        py-2
      "
    >
      <div
        class="
          header__container-content
          w-75
          d-flex
          justify-content-between
          align-items-center
        "
      >
        <AppLogo class="header__app-logo"></AppLogo>
        <div v-if="this.user.isAuth === false">
          <router-link :to="{ name: 'home' }">Home</router-link>
        </div>
        <div v-else>
          <router-link :to="{ name: 'credentials-index' }" class="mx-2"
            >Home</router-link
          >

          <router-link :to="{ name: 'account-index' }">
            <img
              src="/images/profile-icon.svg"
              alt="profile ilustrative icon"
              width="32px"
              height="32px"
            />
            {{ this.user.userData.name }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AppLogo from "./app-logo.vue";

export default {
  components: {
    AppLogo,
  },
  data() {
    return {
      user: this.$store.state.user,
    };
  },
};
</script>