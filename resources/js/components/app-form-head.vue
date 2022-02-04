<style scoped>
.form-head__container {
    padding: 1rem 9rem;
    background-color: white;
    display: inline-block !important;
    text-align: center;
    box-shadow: 0px 5px 5px var(--app-black);
    border-radius: 0.5rem 0.5rem 0rem 0rem;
}

@media (max-width: 520px) {
.form-head__container {
  padding: 1rem 3.5rem !important;
}
}

@media (max-width: 450px) {
.form-head__container{
  padding: 1rem 2rem !important;
}
}
</style>

<template>
    <div>
        <div class="form-head__container d-flex flex-column align-items-center justify-content-center">
            <img class="img-fluid" width="55px" height="55px" :src="imageUrl" alt="ilustrative form head icon">
            <div class="form-head__text">
                <h1 class="my-1 fs-4">{{headText}}</h1>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        imageUrl: {
            type: String,
            default: '/images/lock-icon.svg'
        },
        headText: {
            type: String,
            default: 'Form headline.'
        }
    }
}
</script>