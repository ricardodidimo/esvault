<style scoped>
.loading__container{
  color: var(--app-blue);
  font-family: var(--app-action-font);
}

.loading__box {
  width: 30px;
  height: 30px;
  animation: example 3s infinite;
  background-color: var(--app-blue);
  border-radius: 10%;
}
@keyframes example {
  0% {
    transform: rotate(20deg);
  }
  25% {
    transform: rotate(45deg);
  }
  50% {
    transform: rotate(90deg);
  }
  75% {
    transform: rotate(-120deg);
  }
  100% {
    transform: rotate(20deg);
  }
}
</style>

<template>
  <div class="fs-4">
    <div
      class="loading__container d-flex justify-content-center align-items-center"
    >
      <div class="loading__box me-3"></div>
      <p class="px-3">Loading...</p>
    </div>
  </div>
</template>

<script>
export default {};
</script>