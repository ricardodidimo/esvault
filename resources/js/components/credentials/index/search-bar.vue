<style scoped>
.search-container {
    width: 85%;
}
.search-input {
    width: 90%;
    background-color: var(--app-black);
    color: var(--app-blue);
    border: none;
    border-bottom: 1px solid white;
}
.search-btn {
    border-radius: 0px 0.5rem 0.5rem 0px ;
    padding: 0rem 0.5rem;
}

.search-btn > img{
    width: 90%;
}

.loading-placeholder-search {
    width: auto !important;
}

@media(max-width: 767px) {
    .loading-wrap {
        flex-direction: column;
        justify-content: flex-start !important;
        margin-top: 1rem;
    }
    .search-btn > img{
        width: 65%;
    }
    .search-container {
        width: auto;
        justify-content: start !important;
    }
    .loading-placeholder-search {
        margin-bottom: 1rem;
        justify-content: start !important;
    }
}

@media (max-width: 450px) {
  .loading-placeholder-navigation {
    width: 80%;
    font-size: 0.7rem;
  }
}
</style>

<template>
    <div class="search-container">
        <form v-on:submit.prevent="searchCallback" class="d-flex w-100 justify-content-end loading-wrap">
            
            <LoadingPlaceholder class="loading-placeholder-search mx-2" v-if="fetching">
            </LoadingPlaceholder>
            
            <div class="d-flex justify-content-end">
                <input
                    type="text"
                    placeholder="Search by title..."
                    id="title"
                    name="title"
                    class="search-input me-1"
                />
                <button type="submit" class="search-btn">
                    <img src="/images/search-icon.svg" alt="Get result for search">
                </button>
            </div>
        </form>
    </div>

</template>

<script>
import LoadingPlaceholder from "../../app-loading-placeholder.vue";

export default {
    components: {
        LoadingPlaceholder
    },
    data() {
        return {
            fetching: false
        }
    },
    methods: {
        setRequestingState: function () {
            this.fetching = true;
        },
        setDoneState: function () {
            this.fetching = false;
        },
        searchCallback: function (formObj) {
            this.$emit('search-submit', formObj, this);
        }
    }
}
</script>