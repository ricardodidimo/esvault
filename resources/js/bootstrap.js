window._ = require('lodash');
import app from "./app.js";

try {
    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

/**
 * Correction 'middleware' for expired or abruptly deleted session which makes
 * the application incorrectly identify user as authenticated.
 */
window.axios.interceptors.response.use(function (response) {
    return response;
  }, function (error) {
    const response = error.response;
    const isSeeAsAuth = app.$store.state.user.isAuth;
    if (
        (response.status === 401 ||
        (response.status === 500 && response.data.data === 'CSRF token mismatch.')) && 
        isSeeAsAuth
    ) {
        app.$store.commit('deprecateUser');
        app.$router.push({name: 'account-login'});
    }
    return Promise.reject(error);
  });

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
