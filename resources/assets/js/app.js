window.Vue = require('vue');
window.axios = require('axios');
window.Vuex = require('vuex');

Vue.use(require('vue-moment'));

import InvoiceNodeAppComponent from './components/InvoiceNodeAppComponent.vue';

Vue.component('invoicenodeapp', InvoiceNodeAppComponent);

import store from './store/app-store.js'

new Vue({
    el: "#invoicenode-app",
    store
});
