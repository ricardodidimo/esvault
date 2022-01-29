<style scoped>
    p {
        margin-bottom: 0px;
    }
    .display-head {
        background-color: white;
        color: var(--app-black);
        border-radius: 0.5rem;
    }

    .display-head > img{
        cursor: pointer;
    }

    .display-title{
        width: 90%;
    }
    .display-body {
        background-color: var(--app-black);
        color: white;
    }
    .credential-actions {
        cursor: pointer;
        width: 23%; 
    }

    @media (max-width: 991px) {
        .credential-actions {
            width: 35%;
        }
    }

    @media(max-width: 767px) {
        .credential-actions {
            width: 50%;
        }
    }

    @media(max-width: 400px) {
        .credential-actions {
            width: 60%;
        }
    }

</style>

<template>
    <div class="py-2">
        <div class="w-100 display-head d-flex justify-content-center align-items-center">
            <div class="display-title d-flex justify-content-center">
                <p class="fs-4">{{title}}</p>
            </div>

            <img src="images/arrow-right.svg" width="25px" height="25px" alt="Icon to get details page for credential" v-on:click="goToDetails">
        </div>
        <div class="w-100 display-body d-flex">
            <div class="py-2 w-25 d-flex justify-content-center align-items-center">
                <img 
                    v-on:click="getCredential('first_claim')" 
                    src="images/username-icon.svg"
                    alt="Icon for copying first claim from credential" 
                    class="pe-3 credential-actions"
                >
                <img 
                    v-on:click="getCredential('second_claim')" 
                    src="images/password-icon.svg" 
                    alt="Icon for copying second claim from credential" 
                    class="pe-3 credential-actions"
                >
            </div>
            <div class="w-75">
                <p>{{displayDescription}}</p>
            </div>
        </div> 
    </div>

</template>

<script>
export default {
    props: {
        id: {
            type: Number,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        description: {
            type: String,
            required: false
        },
        first_claim: {
            type: String,
            required: true
        },
        second_claim: {
            type: String,
            required: true
        },
    },
    computed: {
        displayDescription: function () {
            return this.description ?? 'No description.';
        }
    },
    methods: {
        getCredential: async function (credentialFlag) {
            await navigator.clipboard.writeText(this[credentialFlag]);
        },
        goToDetails: function () {
            localStorage.setItem('credential-info', JSON.stringify({
                id: this.id,
                title: this.title,
                description: this.description,
                first_claim: this.first_claim,
                second_claim: this.second_claim
            }));
            this.$router.push({name: 'show-credentials'});
        }
    }
}
</script>