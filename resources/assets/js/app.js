window.Vue = require('vue');
window.axios = require('axios');
window.Vuex = require('vuex');
Vue.use(Vuex);
Vue.use(require('vue-moment'));

import InvoiceNovaAppComponent from './components/InvoiceNovaAppComponent.vue';

Vue.component('invoicenovaapp', InvoiceNovaAppComponent);

import store from './store/app-store.js'

new Vue({
    el: "#invoicenova-app",
    store
});
