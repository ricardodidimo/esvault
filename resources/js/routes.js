import Home from "./views/home.vue";

import Account from "./views/account/index.vue";
import Register from "./views/account/register.vue";
import Login from "./views/account/login.vue";
import VerifyEmail from "./views/account/verify-email.vue";
import VerifiedEmail from "./views/account/verified-message.vue";
import ConfirmDelete from "./views/account/confirm-delete.vue";

import CredentialsIndex from "./views/credentials/index.vue";
import CredentialsCreate from "./views/credentials/create.vue";
import CredentialView from "./views/credentials/view.vue";

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'account-index',
        path: '/account/',
        component: Account
    },
    {
        name: 'account-register',
        path: '/account/register',
        component: Register
    },
    {
        name: 'account-login',
        path: '/account/login',
        component: Login
    },
    {
        name: 'account-verify',
        path: '/account/verify',
        component: VerifyEmail
    },
    {
        name: 'account-verified',
        path: '/account/verified',
        component: VerifiedEmail
    },
    {
        name: 'account-confirm',
        path: '/account/confirm',
        component: ConfirmDelete
    },
    {
        name: 'credentials-index',
        path: '/credentials/',
        component: CredentialsIndex
    },
    {
        name: 'credentials-create',
        path: '/credentials/create',
        component: CredentialsCreate
    },
    {
        name: 'credentials-view',
        path: '/credentials/view/:credentialId',
        component: CredentialView
    }

];