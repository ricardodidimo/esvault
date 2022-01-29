export default {
    methods: {
        shouldBeAuthenticated: async function (needsEmailVerification = true) {
            try {
                const response = await axios.get("/api/isAuth");
                if (response.status === 200) {
                    return true;
                }
            } catch (e) {
                const errorResponse = e.response;
                if (errorResponse.status === 401) {
                    this.$router.push({ name: "login"});
                    return false;
                }

                if (errorResponse.status === 403 && needsEmailVerification) {
                    this.$router.push({ name: 'verify-email'});
                    return false;
                }
                
                return true;
            }
        },
        shouldBeGuest: async function () {
            try {
                const response = await axios.get("/api/isAuth");
                if (response.status === 200) {
                    this.$router.push( {name: 'index-credentials'});
                    return false;
                }
            } catch (e) {
                const errorResponse = e.response;
                if (errorResponse.status === 401) {
                    return true;
                }

                if (errorResponse.status === 403) {
                    this.$router.push( {name: 'index-credentials'});
                    return false;
                }
            }
        }
    }
};