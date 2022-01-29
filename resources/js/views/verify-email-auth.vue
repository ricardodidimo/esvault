<style scoped>
* {
  box-sizing: initial !important;
}
.main-title {
  text-align: center;
  margin: 3rem 0rem;
}
.go-btn {
  border-radius: 5px;
  background: var(--app-black);
  color: white;
  border: none;
}
.text-wall {
  width: 35% !important;
}
.main-container {
  height: 80vh;
}
@media (max-width: 760px) {
  .main-title {
    margin: 1rem 0rem;
  }
  .text-wall-container {
    flex-direction: column;
  }
  .text-wall {
    flex-direction: row !important;
    width: 100% !important;
    margin-bottom: 1rem;
    justify-content: center !important;
  }
  .text-wall > img {
    width: 15% !important;
  }
}
</style>

<template>
  <div
    class="
      my-2
      main-container
      d-flex
      flex-column
      justify-content-center
      align-items-center
    "
    v-if="loaded"
  >
    <main class="w-100 d-flex justify-content-center text-wall-container">
      <div class="d-flex flex-column align-items-center text-wall">
        <img
          src="/images/idea.svg"
          alt="a lamp icon meaning good idea"
          class="img-fluid"
        />
        <div class="text-center">
          <h1>Hey! Verify Your Email</h1>
          <p>{{ verifyText }}</p>
        </div>
      </div>
    </main>
    <footer class="w-100 d-flex justify-content-center mt-3">
      <button class="go-btn p-2" v-on:click="resendEmail">
        Resend the email
      </button>
    </footer>
  </div>
</template>

<script>
import axiosHelper from "../mixins/axiosHelper";
import viewAuthorization from "../mixins/viewAuthorization";
export default {
  async beforeMount() {
    await this.shouldBeAuthenticated(false);
    await this.makeRequest(this.httpMethods.get, "/api/email/is-verified", {
      handle200: {
        handlerFunc: this.handle200IsVerified,
      },
    });
  },
  mixins: [axiosHelper, viewAuthorization],
  data() {
    return {
      loaded: false,
      verifyText: `Before you progress further is necessary to validate 
                    the account email address by clicking on the link whitin an email 
                    sent to your inbox by us.`,
    };
  },
  methods: {
    resendEmail: async function () {
      this.verifyText = `Whitin a few seconds we will be sending you
                    another confirmation email. Keep attention to your inbox.`;
      await this.makeRequest(
        this.httpMethods.post,
        "/api/email/verification-notification"
      );
    },
    handle200IsVerified: function (response) {
      if (response.data.data.isVerified === true) {
        this.$router.push({ name: "index-credentials" });
      }

      this.loaded = true;
    },
  },
};
</script>