<style scoped>
.cards__item {
  max-width: 33%;
}
.a-link {
  opacity: 0.5;
  text-decoration: underline;
}
.blue-background {
  width: 97vw !important;
  border-radius: 0rem 0rem 1rem 1rem;
  padding: 8rem 8rem;
  transform: skewX(3.5deg);
  background-color: var(--app-blue);
  color: white;
}
footer > p {
  opacity: 0.5;
}
@media (max-width: 990px) {
  .cards__container {
    flex-direction: column;
    padding: 0.5rem 0rem !important;
  }
  .cards__item {
    max-width: 80%;
    margin-bottom: 1rem;
  }
}
</style>

<template>
  <div
    class="
      home__container
      d-flex
      flex-column
      justify-content-center
      align-items-center
    "
  >
    <main
      class="
        blue-background
        d-flex
        flex-column
        justify-content-center
        align-items-center
      "
    >
      <h1 class="fs-3 mb-4">
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
      </h1>
      <div>
        <router-link :to="{ name: 'account-register' }">
          <AppPrimaryButton class="fs-5">Get started</AppPrimaryButton>
        </router-link>
        <router-link :to="{ name: 'account-login' }">
          <AppPrimaryButton class="fs-5">Already in</AppPrimaryButton>
        </router-link>
      </div>
    </main>
    <footer class="mt-5 d-flex flex-column align-items-center justify-content-center">
      <div>
        <a href="https://github.com/ricardodidimo">
        <img width="55px" height="55px" src="images/logo__github-icon.svg" alt="github icon">
        </a>
        <a href="https://www.linkedin.com/in/ricardo-didimo-558630209">
        <img width="55px" height="55px" src="images/logo__linkedin-icon.svg" alt="linkedin icon">
        </a>
      </div>
      <p>This application is a demonstration.</p>
    </footer>
  </div>
</template>

<script>
import AppCard from "../components/app-card.vue";
import AppPrimaryButton from "../components/app-primary-btn.vue";

export default {
  components: {
    AppCard,
    AppPrimaryButton,
  },
  async mounted() {
    if ((await this.$store.dispatch("checkForGuest")) != true) {
      return;
    }
    this.$emit("component-ready");
  },
};
</script>