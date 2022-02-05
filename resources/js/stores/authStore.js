const store = {
    state: {
        user: {
            userData: null,
            isGuest: false,
            isAuth: false,
            isVerified: false,
            isOutdated: true
        }
    },
    mutations: {
        populateUserState(state, userPayload) {
            for (var prop in userPayload) {
                state.user[prop] = userPayload[prop];
            }
        },
        deprecateUser(state) {
            state.user.isOutdated = true;
        }
    },
    actions: {
        async checkUserState(context) {
            if (context.state.user.isOutdated === false) {
                return;
            }
            try {
                const response = await axios.get("/api/account");
                if (response.status === 200) {
                    const retrievedUser = response.data.data;
                    const populateData = {
                        userData: retrievedUser,
                        isAuth: true,
                        isVerified: retrievedUser.email_verified_at != null
                    }
                    context.commit('populateUserState', populateData);
                }
            } catch (e) {
                const response = e.response;
                if (response.status === 401) {
                    const populateData = {
                        userData: null,
                        isGuest: true,
                        isAuth: false,
                        isVerified: false
                    }
                    context.commit('populateUserState', populateData);
                }
            } finally {
                context.commit('populateUserState', {isOutdated: false});
            }
        },
        async checkForGuest(context) {
            
            if (context.state.user.isOutdated) {
                await context.dispatch('checkUserState');
            }

            if (context.state.user.isGuest === false) {
                window.location.replace("/credentials/");
                return;
            }
            return true;
        },
        async checkForAuthenticated(context) {  
            if (context.state.user.isOutdated) {
                await context.dispatch('checkUserState');
            }

            if (context.state.user.isAuth === false) {
                window.location.href = 'https://xvault-app.herokuapp.com/account/login';
                return;
            }

            return true;
        },
        async checkForVerifiedUser(context) {
            
            if (context.state.user.isOutdated) {
                await context.dispatch('checkUserState');
            }

            if (context.state.user.isVerified === false) {
                window.location.replace("/account/verify/");
                return;
            }
            return true;
        },
        async checkForNonVerifiedUser(context) {
            
            if (context.state.user.isOutdated) {
                await context.dispatch('checkUserState');
            }

            if (context.state.user.isVerified != false) {
                window.location.replace("/credentials/");
                return;
            }
            return true;
        }
    }
};

export default store;