<style scoped>
.cards__item {
  max-width: 33%;
}
.a-link {
  opacity: 0.5;
  text-decoration: underline;
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
  <div class="home__container d-flex flex-column justify-content-center align-items-center">
    <div
      class="
        d-flex
        justify-content-center
        align-items-center
        py-5
        cards__container
      "
    >
      <AppCard class="cards__item mx-2" cardTitle="For better security">
        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Assumenda
        consequatur minima est nihil reprehenderit hic modi quis tenetur,
        laborum mollitia omnis, rem adipisci qui, ad molestiae explicabo eum!
        Distinctio, vitae.
      </AppCard>
      <AppCard
        class="cards__item mx-2"
        imageUrl="images/idea-icon.svg"
        cardTitle="For better security"
      >
        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Assumenda
        consequatur minima est nihil reprehenderit hic modi quis tenetur,
        laborum mollitia omnis, rem adipisci qui, ad molestiae explicabo eum!
        Distinctio, vitae.
      </AppCard>
      <AppCard
        class="cards__item mx-2"
        imageUrl="images/lock-icon.svg"
        cardTitle="For better security"
      >
        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Assumenda
        consequatur minima est nihil reprehenderit hic modi quis tenetur,
        laborum mollitia omnis, rem adipisci qui, ad molestiae explicabo eum!
        Distinctio, vitae.
      </AppCard>
    </div>
    <div
      class="w-100 d-flex flex-column align-items-center justify-content-center"
    >
      <router-link :to="{name: 'account-register'}">
        <AppPrimaryButton
          class="fs-4"
        >Get started</AppPrimaryButton>
      </router-link>

      <router-link :to="{name: 'account-login'}" class="a-link fs-5">Already in</router-link>
    </div>
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
    await this.$store.dispatch('checkUserState');
    this.$emit("component-ready");
  },
};
</script>