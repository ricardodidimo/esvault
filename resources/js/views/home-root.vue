<style scoped>
    * {
        box-sizing: initial !important;
    }
    .main-title{
        text-align: center;
        margin: 3rem 0rem;
    }
    .go-btn{
        border-radius: 5px;
        background: var(--app-black);
        color: white;
        border: none;
    }
    .text-wall{
        width: 35% !important;

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
    <div>
        <h1 class=" main-title">Safe & Agile</h1>
        <main class="w-100 d-flex justify-content-center text-wall-container">
            <div class="d-flex flex-column align-items-center me-2 text-wall">
                <img src="/images/bi_shield-lock.svg" alt="a shield icon meaning safety concerns" 
                    class="img-fluid">
                <div class="text-center">
                    <h2>For better security</h2>
                    <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Natus, esse dolore? Voluptatibus suscipit maxime repellendus rem natus laudantium ipsum repudiandae.</small>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center me-2 text-wall">
                <img src="/images/idea.svg" alt="a lamp icon meaning good idea" class="img-fluid">
                <div class="text-center">
                    <h2>In an easy way</h2>
                    <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Natus, esse dolore? Voluptatibus suscipit maxime repellendus rem natus laudantium ipsum repudiandae.</small>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center me-2 text-wall">
                <img src="/images/lock.svg" alt="a lock icon meaning security" class="img-fluid">
                <div class="text-center">
                    <h2>In a secure vault</h2>
                    <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Natus, esse dolore? Voluptatibus suscipit maxime repellendus rem natus laudantium ipsum repudiandae.</small>
                </div>
            </div>
        </main>
        <footer class="w-100 d-flex justify-content-center mt-3">  
            <router-link :to="{name: 'register'}" class="nav-item nav-link">
                <button class="go-btn p-3">
                    Get started
                </button>
            </router-link>
        </footer>
    </div>
</template>

<script>
    export default {
    }
</script>