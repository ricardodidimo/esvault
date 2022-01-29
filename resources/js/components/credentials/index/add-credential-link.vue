<style scoped>
.add-link {
  border-radius: 0.5rem 0rem 0rem 0.5rem;
  margin: 0px;
  padding: 0px 10px;
}

.add-link > img {
  width: 90%;
}
</style>

<template>
  <div>
    <button type="submit" class="add-link">
      <router-link :to="{name: 'create-credentials'}">
        <img src="/images/add-link.svg" alt="Icon to get new credential's form" />
      </router-link>
    </button>
  </div>
</template>

<script>
export default {};
</script>