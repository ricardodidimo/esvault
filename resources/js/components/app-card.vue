<style scoped>
.card__container {
  padding: 2rem;
  background-color: white;
  display: inline-block !important;
  text-align: center;
  box-shadow: 0px 3px 3px var(--app-black);
  border-radius: 0.5rem;
}
.card__container:hover {
  box-shadow: 0px 3px 3px var(--app-blue);
}

</style>

<template>
  <div>
    <div
      class="
        card__container
        d-flex
        flex-column
        align-items-center
        justify-content-center
      "
    >
      <img class="img-fluid mb-2" :src="imageUrl" alt="ilustrative card icon" />
      <div class="">
        <h1 class="my-1 fs-5">{{cardTitle}}</h1>
        <slot>Card Description</slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    cardTitle: {
      type: String,
      required: true,
      default: "card title",
    },
    imageUrl: {
      type: String,
      default: '/images/shield-lock.svg'
    }
  },
};
</script>