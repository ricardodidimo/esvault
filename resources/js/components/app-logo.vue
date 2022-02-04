<style scoped>
.logo__container > a {
  margin: 0px 10px;
  cursor: pointer;
}

.logo__text {
  font-family: "Righteous", cursive;
}
.logo__text > small {
  color: var(--app-blue);
}
</style>

<template>
  <div>
    <div class="d-flex logo__container">
      <a href="https://github.com/ricardodidimo">
        <img class="img-fluid" src="/images/logo__github-icon.svg" alt="author github icon" />
      </a>
      <div class="d-flex logo__text">
        <small class="fs-4">x</small>
        <h1>Vault</h1>
      </div>
      <a href="https://www.linkedin.com/in/ricardo-didimo-558630209/">
        <img class="img-fluid" src="/images/logo__linkedin-icon.svg" alt="author linkedin icon" />
      </a>
    </div>
  </div>
</template>

<script>
export default {};
</script>