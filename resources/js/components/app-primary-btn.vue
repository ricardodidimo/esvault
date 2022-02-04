<style scoped>
.primary-btn__container {
  display: inline-block;
}

button {
  padding: 0.3rem 1.5rem;
  display: inline-block;
  text-align: center;
  border-radius: 0.5rem;
  color: white;
  font-weight: bold;
}
</style>

<template>
  <div class="primary-btn__container">
    <button :style="btnColor">
      <slot></slot>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    color: {
      type: String | null,
      default: null
    },
  },
  computed: {
    btnColor() {
      return {
        "background-color": this.color ?? 'rgb(0,0,0)',
      };
    },
  },
};
</script>