window.Vue = require('vue');
window.axios = require('axios');

new Vue({
    el: "#edit-invoice-app",
    data: function() {
        return {
            invoice: {},
            beForm: {
                description: null,
                charge: null,
                position: null,
            },
            alert: {
                message: null,
                type: null,
            },
        }
    },
    created: function() {
        this.invoice = $('#edit-invoice-app').data('jsoninvoicedata');
    },
    methods: {
        removeMessage: function() {
            this.alert.message = null;
            this.alert.messageType = null;
        },
        updateInvoiceAttributes: function() {
            axios.put('/api/invoices/'+this.invoice.id, this.invoice)
                .then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.alert.message = 'Invoice has been updated.';
                        this.alert.type = 'success';
                        this.invoice = response.invoice;
                    } else {
                        this.alert.message = response.error;
                        this.alert.type = 'error';
                    }
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                });
        },
        createNewBillEntry: function(){
            this.beForm.position = this.invoice.bill_entries.length;
            axios.post('/api/invoices/'+this.invoice.id+'/billentries', this.beForm)
                .then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.alert.message = 'New bill entry has been added to this invoice';
                        this.alert.type = 'success';
                        this.invoice = response.invoice;
                    } else {
                        this.alert.message = response.error;
                        this.alert.type = 'error';
                    }
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                });
        },
        removeExistingBillEntry: function(id) {
            axios.delete('/api/invoices/'+this.invoice.id+'/billentries/'+id)
                .then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.alert.message = 'Bill entry has been removed.';
                        this.alert.type = 'success';
                        this.invoice = response.invoice;
                    } else {
                        this.alert.message = response.error;
                        this.alert.type = 'error';
                    }
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                });
        },
        updateBillEntriesOrder: function() {
            // TODO to arrange the display order for the billentries in this invoice
        },
        reloadInvoiceObj: function() {
            this.refreshAlert();
            axios.get('/api/invoices/'+this.invoice.id)
                .then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.invoice = response.invoice;
                    } else {
                        this.alert.message = response.error;
                        this.alert.type = 'error';
                    }
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                });
        },
        issueInvoice: function() {
            this.refreshAlert();
            this.invoice.status = 'issued';
            axios.put('/api/invoices/'+this.invoice.id, this.invoice)
                .then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.alert.message = 'Invoice has been issued.';
                        this.alert.type = 'success';
                        this.invoice = response.invoice;
                    } else {
                        this.alert.message = response.error;
                        this.alert.type = 'error';
                    }
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                });
        },
        cancelInvoice: function() {
            this.refreshAlert();
            this.invoice.status = 'cancelled';
            axios.put('/api/invoices/'+this.invoice.id, this.invoice)
                .then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.alert.message = 'Invoice has been cancelled.';
                        this.alert.type = 'success';
                        this.invoice = response.invoice;
                    } else {
                        this.alert.message = response.error;
                        this.alert.type = 'error';
                    }
                })
                .catch((error) => {
                    console.log(error);
                    alert(error);
                });
        },
        refreshAlert: function() {
            this.alert = {
                message: null,
                type: null,
            };
        }
    }
});