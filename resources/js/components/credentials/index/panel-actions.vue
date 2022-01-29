<style scoped>

</style>

<template>
    <div class="w-100 d-flex justify-content-start">
        <router-link
            :to="{name: 'index-credentials'}"
            class="fs-3 pe-4 pt-2"
            >Vault</router-link
        >
        <router-link :to="{name: 'account'}" class="fs-3 pe-4 pt-2"
            >Account</router-link
        >
    </div>
</template>

<script>
export default {
}
</script>