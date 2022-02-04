require('./bootstrap');

import Vue from 'vue'
import Vuex from 'vuex';
import App from './App.vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import {routes} from './routes';
import authStore from './stores/authStore';
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.prototype.axios = axios;

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

const store = new Vuex.Store(authStore);
const app = new Vue({
    el: '#app',
    router: router,
    store: store,
    render: h => h(App)
});

export default app;