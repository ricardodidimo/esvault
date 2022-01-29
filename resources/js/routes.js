import Home from './views/home-root.vue';

import Register from './views/register-auth.vue';
import Login from './views/login-auth.vue';
import VerifyEmail from './views/verify-email-auth.vue';
import Account from './views/index-account.vue';

import CreateCredentials from './views/create-credentials.vue';
import Index from './views/index-credentials.vue';
import Show from './views/show-credentials.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/auth/register',
        component: Register
    },
    {
        name: 'verify-email',
        path: '/auth/verify',
        component: VerifyEmail
    },
    {
        name: 'account',
        path: '/auth/account',
        component: Account
    },
    {
        name: 'login',
        path: '/auth/login',
        component: Login
    },
    {
        name: 'create-credentials',
        path: '/credentials/create',
        component: CreateCredentials
    },
    {
        name: 'index-credentials',
        path: '/credentials',
        component: Index
    },
    {
        name: 'show-credentials',
        path: '/credentials/show',
        component: Show
    }
];