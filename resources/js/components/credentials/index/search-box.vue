<style scoped>
.search__input {
  background-color: unset;
  border: none;
  border-bottom: 1px solid black;
}
.search__btn {
  background-color: black;
  color: white;
  border-radius: 0 0.5rem 0.5rem 0;
}
</style>
<template>
  <div>
    <form class="px-2" v-on:submit.prevent="search">
      <input
        type="text"
        class="search__input"
        placeholder="Search credential..."
      />
      <button class="search__btn px-2">
        <img
          width="22px"
          height="22px"
          src="/images/search-icon.svg"
          alt="trigger search for credential by title"
        />
      </button>
    </form>
  </div>
</template>

<script>
export default {
  methods: {
    search: function (formElement) {
      const titleParam = formElement.target.elements[0].value;

      const searchUrl =
        titleParam === ""
          ? "/api/credentials"
          : "/api/credentials/" + titleParam;
      this.$emit("requireSearch", searchUrl);
    },
  },
};
</script>
