import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        invoices: [],
    },
    mutations: {
        SET_INVOICES (state, invoicesList) {
            state.invoices = invoicesList;
        },
        loadInvoices (state) {
            axios.get('/api/invoices/?page=1')
                .then((response) => {
                    response = response.data;
                    if (response.success) {
                        state.invoices = response.invoices.data;
                    } else {
                        console.error(response.error);
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
        },
    },
    getter:{
        GET_INVOCIE: state => {
            return state.invoices;
        }
    }
});

export default store