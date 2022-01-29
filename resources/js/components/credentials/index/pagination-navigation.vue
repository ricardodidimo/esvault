<style scoped>
.pagination-actions {
  cursor: pointer;
}

@media (max-width: 767px) {
  .pagination-actions {
    width: 43%;
    
  }
  .loading-placeholder-navigation {
      margin-left: 2rem;
  }
}

@media (max-width: 450px) {
  .pagination-actions {
    width: 53%;
  }
  .loading-placeholder-navigation {
    width: 80%;
    font-size: 0.7rem;
  }
}

@media (max-width: 370px) {
  .pagination-actions {
    width: 63%;
  }
}

</style>

<template>
  <div class="w-25 d-flex">
    <img
      src="/images/navigation-left-disable.svg"
      class="pagination-actions pe-2"
      alt="There isn't a previous page"
      v-if="previousUrl === null"
    />
    <img
      src="/images/navigation-left-active.svg"
      class="pagination-actions pe-2"
      alt="Get previous page"
      v-if="previousUrl != null"
      v-on:click="navigationCallback(previousUrl)"
    />
    <img
      src="/images/navigation-right-disable.svg"
      class="pagination-actions pe-2"
      alt="There isn't a next page"
      v-if="nextUrl === null"
    />
    <img
      src="/images/navigation-right-active.svg"
      class="pagination-actions pe-2"
      alt="Get next page"
      v-if="nextUrl != null"
      v-on:click="navigationCallback(nextUrl)"
    />
    <LoadingPlaceholder
      v-if="fetching === true"
      class="loading-placeholder-navigation"
    ></LoadingPlaceholder>
  </div>
</template>

<script>
import LoadingPlaceholder from "../../app-loading-placeholder.vue";

export default {
  components: {
    LoadingPlaceholder,
  },
  props: {
    previousUrl: {
      type: String,
    },
    nextUrl: {
      type: String,
    },
  },
  data() {
    return {
      fetching: false,
    };
  },
  methods: {
    setRequestingState: function () {
      this.fetching = true;
    },
    setDoneState: function () {
      this.fetching = false;
    },
    navigationCallback: function (urlAction) {
      this.$emit("navigation-submit", urlAction, this);
    },
  },
};
</script>