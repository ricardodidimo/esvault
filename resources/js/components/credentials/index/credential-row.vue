<style scoped>
td > p{
  cursor: pointer;
}
td > p:hover {
  color: var(--app-blue);
}
.credential-row__td-description {
  width: 40%;
  line-height: 1.2;
}
.credential-row__btn-copy {
  font-size: 0.7rem;
}

@media (max-width: 470px) {
  .credential-row__td-description {
  width: 60%;
  }
}
</style>

<template>
  <tr>
    <td v-on:click="getView">
      <p><strong>{{ credential.title }}</strong></p>
    </td>
    <td class="credential-row__td-description"  v-on:click="getView">
      <p>{{ displayDescription }}</p>
    </td>
    <td>
      <AppButton
        class="credential-row__btn-copy"
        v-on:click.native="getClaim('first_claim')"
      >COPY</AppButton>
    </td>
    <td>
      <AppButton
        class="credential-row__btn-copy"
        v-on:click.native="getClaim('second_claim')"
      >COPY</AppButton>
    </td>
  </tr>
</template>

<script>
import AppButton from "../../app-primary-btn.vue";
export default {
  components: {
    AppButton,
  },
  props: {
    credential : {
      type: Object
    }
  },
  methods: {
    getClaim: async function (claimFlag) {
      await navigator.clipboard.writeText(this.credential[claimFlag]);
    },
    getView: function () {
      this.$router.push({ name: 'credentials-view', params: { credentialId: this.credential.id } });
    }
  },
  computed: {
    displayDescription: function () {
      return this.credential.description ?? "No description";
    },
  },
};
</script>