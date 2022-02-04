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
  }
  .cards__item {
    max-width: 80%;
    margin-bottom: 1rem;
  }
}
</style>

<template>
  <div>
    <div
      class="
        d-flex
        justify-content-center
        align-items-center
        py-5
        cards__container
      "
    >
      <AppCard
        class="cards__item mx-2"
        imageUrl="/images/idea-icon.svg"
        cardTitle="Verify your email"
      >
        <p>{{ descriptionText }}</p>
      </AppCard>
    </div>
    <div
      class="w-100 d-flex flex-column align-items-center justify-content-center"
    >
      <button class="a-link fs-5" v-on:click="resend">Resend Email</button>
    </div>
  </div>
</template>

<script>
import AppCard from "../../components/app-card.vue";
import AppPrimaryButton from "../../components/app-primary-btn.vue";

export default {
  components: {
    AppCard,
    AppPrimaryButton,
  },
  data() {
    return {
      descriptionText:
        "Before your proceed further, click on the verification link send to your email.",
    };
  },
  async mounted() {
    if (
      (await this.$store.dispatch("checkForAuthenticated")) != true ||
      (await this.$store.dispatch("checkForNonVerifiedUser")) != true
    ) {
      return;
    }

    this.$emit("component-ready");
  },
  methods: {
    resend: async function () {
      try {
        this.descriptionText =
          "In a few seconds we will be resending a email to you.";
        await axios.get("/sanctum/csrf-cookie");
        await axios.post("/api/email/verification-notification");
      } catch (e) {}
    },
  },
};
</script>