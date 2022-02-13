<style scoped>
.account-update__btn-extension {
  margin-right: 7.5rem;
}
</style>

<template>
  <FormBody headerText="Update profile" class="mb-3 mx-3" v-on:submiting="updateAccountSubmit">
    <div class="form-group mb-2">
      <label for="name">Name</label>
      <input
        type="name"
        class="form-control"
        id="name"
        name="name"
        aria-describedby="name"
        v-model="userInfo.name"
      />
      <AppValidation
        :validationErrorsField="validationErrors.name"
      ></AppValidation>
    </div>
    <div class="form-group mb-2">
      <label for="email">Email address</label>
      <input
        type="email"
        class="form-control"
        id="email"
        name="email"
        aria-describedby="emailHelp"
        v-model="userInfo.email"
      />
      <AppValidation
        :validationErrorsField="validationErrors.email"
      ></AppValidation>
    </div>
    <div class="form-group mb-2">
      <label for="password">Password</label>
      <input
        type="password"
        class="form-control"
        id="password"
        name="password"
        autocomplete="on"
        v-model="userInfo.password"
      />
      <AppValidation
        :validationErrorsField="validationErrors.password"
      ></AppValidation>
    </div>

    <div class="form-group mb-2" v-if="hasRequestedCurrentPassword">
      <label for="current_password">Type your current password</label>
      <input
        type="password"
        class="form-control"
        id="current_password"
        name="current_password"
        autocomplete="on"
      />
      <AppValidation
        :validationErrorsField="validationErrors.current_password"
      ></AppValidation>
    </div>
    <div>
      <AppPrimaryButton class="account-update__btn-extension"
        >Update profile</AppPrimaryButton
      >
    </div>
  </FormBody>
</template>

<script>
import FormBody from "../../app-form-body.vue";
import AppPrimaryButton from "../../app-primary-btn.vue";
import AppValidation from "../../app-validation.vue";

export default {
  components: {
    AppPrimaryButton,
    FormBody,
    AppValidation,
  },
  data() {
    return {
      updatePayload: {},
      hasRequestedCurrentPassword: false,
      validationErrors: {
        name: [],
        email: [],
        password: [],
        current_password: [],
      },
      lastChangeUserInfo: {
        name: "",
        email: "",
        password: "**********",
      },
      userInfo: {
        name: "",
        email: "",
        password: "**********",
      },
    };
  },
  methods: {
    populateUpdateValidation: function (errorsListObject = {}) {
      this.validationErrors = {
        name: errorsListObject?.name ?? [],
        email: errorsListObject?.email ?? [],
        password: errorsListObject?.password ?? [],
        current_password: errorsListObject?.current_password ?? [],
      };
    },
    shouldRequestCurrentPassword: function () {
      if (this.hasRequestedCurrentPassword === false) {
        this.hasRequestedCurrentPassword = true;
        return true;
      }
      return false;
    },
    inputHasChanged(inputName, newInputValue) {
      const currentValue = this.lastChangeUserInfo[inputName];
      return newInputValue != currentValue;
    },
    getValidInput(allInput) {
      const payload = {};
      for (var input in allInput) {
        const inputAsValue = allInput[input];
        if (
          inputAsValue != "" &&
          inputAsValue != null &&
          this.inputHasChanged(input, inputAsValue) === true
        ) {
          payload[input] = inputAsValue;
        }
      }
      if (payload.current_password === undefined) {
        payload.current_password = allInput.current_password;
      }
      return payload;
    },
    /**
     * To be an actual update means to have a additional field besides current_password.
     */
    isValidUpdate: function (payload) {
      return Object.keys(payload).length > 1;
    },
    isCallWithoutValueChange: function (formComponent) {
      const rawFormData = formComponent.getFormData();
      this.updatePayload = this.getValidInput(rawFormData);

      if (this.isValidUpdate(this.updatePayload) === false) {
        formComponent.setSuccessfulSubmitMessage(
          "No alteration was made or current password not given."
        );
        return true;
      }
      return false;
    },
    initializeFormBeforeSubmit: function (form) {
      form.setToSubmitState();
      form.setSuccessfulSubmitMessage(null);
      this.populateUpdateValidation(null);
    },
    updateAccountSubmit: async function (formComponent) {
      if (
        this.shouldRequestCurrentPassword() ||
        this.isCallWithoutValueChange(formComponent)
      ) {
        return;
      }
      this.initializeFormBeforeSubmit(formComponent);
      try {
        const preRequest = await axios.get("/sanctum/csrf-cookie");
        const response = await axios.put("/api/users", this.updatePayload);
        if (response.status === 204) {
          this.$store.commit("deprecateUser");
          await this.$store.dispatch("checkUserState");
          formComponent.setSuccessfulSubmitMessage(
            "Profile was successfully updated."
          );
          this.hasRequestedCurrentPassword = false;
          this.updateUserInfo(this.updatePayload);
        }
      } catch (e) {
        const response = e.response;
        if (response.status === 400) {
          const errorsListObject = response.data.data;
          this.populateUpdateValidation(errorsListObject);
        }
      } finally {
        formComponent.setToBeginState();
      }
    },
    updateUserInfo(updateData) {
      for (let value in updateData) {
        this.userInfo[value] = updateData[value];
        this.lastChangeUserInfo[value] = updateData[value];
      }
    },
  },
};
</script>