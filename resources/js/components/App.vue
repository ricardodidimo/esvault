<style>
  :root{
    --app-black: #141418;
    --app-blue: #88C3FF;
    --app-orange: #ff9100;
  }
  a {
    color: inherit;
    text-decoration: none;
    cursor: pointer;
  }
  a:hover {
    color: var(--app-blue);
  }
  button {
    border: none;
  }
  .image-container{
      background-image: url('..\\..\\images\\background.svg');
      color: white;
      min-height: 100vh;
      background-size: cover;
  }
  .loading-placeholder {
    height: 80vh;
  }
</style>

<template>
    <div class="image-container">
        <div class="container">
            <AppHeader></AppHeader>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import AppHeader from "./app-header.vue";
export default {
  components: {
    AppHeader
  }
}
</script>