<style scoped>
.loading-container {
  color: var(--app-orange);
  font-weight: bolder;

}

.loading-border{
  border: 1px solid var(--app-orange);
}

.loading-border > p{
  margin: 0px;
}

.loading-box {
  width: 15px;
  height: 15px;
  animation: example 3s infinite;
  background-color: var(--app-orange);
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
  <div class="
      w-100 
      loading-container
      d-flex 
      justify-content-center 
      align-items-center
    ">
    <div class="loading-border d-flex justify-content-center align-items-center">
      <p class="px-3">Loading...</p>
      <div class="loading-box me-3"></div>
    </div>
  </div>
</template>

<script>
export default {};
</script>