<style>
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;800&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Righteous&family=Roboto:wght@300;400;500;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@300;400;500;700&display=swap");

:root {
  --app-black: #001d29;
  --app-blue: #00597e;
  --app-orange: #ee863b;
  --app-text-font: "Roboto", sans-serif;
  --app-action-font: "Anton", sans-serif;
}

body {
  background-color: #f3f3f3;
  font-family: var(--app-text-font);
}

a {
  color: inherit;
  text-decoration: none;
  cursor: pointer;
}
a:hover {
  color: var(--app-blue);
}
p {
  margin: 0;
}
button {
  border: none;
}
.loading__container--page {
  height: 100vh;

}

.loading__container--page > p {
  color: var(--app-blue);
  letter-spacing: 3px;
}

</style>

<template>
  <div>
    <div
      v-if="componentReady === false"
      class="
        loading__container--page
        d-flex
        justify-content-center
        align-items-center
      "
    >
      <LoadingPlaceholder></LoadingPlaceholder>
      <p class="px-3">Loading...</p>
    </div>
    <div v-show="componentReady">
      <AppHeader></AppHeader>
      <div class="container">
        <router-view 
          v-on:component-ready="setComponentToReady"
          v-on:needs-loading="setComponentToLoading"
        ></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import AppHeader from "./components/app-header.vue";
import LoadingPlaceholder from "./components/app-loading-placeholder.vue";

export default {
  components: {
    AppHeader,
    LoadingPlaceholder,
  },
  data() {
    return {
      componentReady: false
    };
  },
  methods: {
    setComponentToReady() {
      this.componentReady = true;
    },
    setComponentToLoading() {
      this.componentReady = false;
    },
  },
  watch:{
    $route (to, from){
        this.componentReady = false;
    }
} ,

};
</script>