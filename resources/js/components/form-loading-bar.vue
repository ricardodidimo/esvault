<style scoped>
    @keyframes loading {
        0%   {width: 0%;}
        25%   {width: 25%;}
        50%   {width: 55%;}
        50%   {width: 75%;}
        100%   {width: 100%;}
    }
    .loading{
        width: 0%;
        background-color: var(--app-blue);
        height: 5px;
        animation-name: loading;
        animation-duration: 60s;
    }
</style>

<template>
    <div class="w-100">
        <div class="loading"></div>
    </div>
</template>

<script>
    export default {}
</script>