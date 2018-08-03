<template>
<div class="ceate-invoice-wrapper">
    <div id="create-invoice-form" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Create New Invoice
                        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close" v-on:click="resetForm()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12 text-center p-3 form-message alert"
                             v-bind:class="{ 'alert-info': (form_msg_type === 'success'), 'alert-danger': (form_msg_type === 'error')}"
                             v-if="form_message != ''"
                             v-on:click="form_message=''"
                             v-html="form_message"
                        >{{ form_message }}</div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Customer/Client Name</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" v-model="form_data.client_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Email Contact</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="email" v-model="form_data.email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Phone Contact</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" v-model="form_data.phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Company</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" v-model="form_data.business_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Business Number</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" v-model="form_data.business_number">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Address</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" v-model="form_data.address">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Is taxable</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="checkbox" v-model="form_data.is_taxable">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="form-inline">Tax Rate</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="number" v-model="form_data.tax_rate">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12">
                        <button class="float-right btn btn-secondary" v-on:click="submitForm()">SUBMIT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        name: "CreateNewInvoiceComponent",
        data: function() {
            return {
                form_message: '',
                form_msg_type: 'success',
                form_data: {
                    client_name: '',
                    email: '',
                    phone: null,
                    business_name: '',
                    business_number: '',
                    address: '',
                    is_taxable: false,
                    tax_rate: 0,
                    template: 'default',
                }
            }
        },
        methods: {
            resetForm: function() {
                this.form_data =  {
                    client_name: '',
                    business_name: '',
                    business_number: '',
                    address: '',
                    is_taxable: false,
                    tax_rate: 0,
                    template: 'default',
                };
            },
            submitForm: function() {
                axios.post('/api/invoices/', this.form_data)
                    .then((response) => {
                        response = response.data;
                        if (response.success) {
                            this.form_msg_type = 'success';
                            this.form_message = 'New Invoice has been created.';
                            this.resetForm();
                            // $('#create-invoice-form').modal('toggle');
                        } else {
                            this.form_msg_type = 'error';
                            if (typeof response.error == 'string')
                            {
                                this.form_message = response.error;
                            } else {
                                this.form_message = '';
                                var errors = [];
                                $.map( response.error, (value, field) => {
                                    errors.push(value.join('<br>'));
                                });
                                this.form_message = errors.join('<br>');
                            }
                        }
                    })
                    .catch((error) => {
                        this.form_msg_type = 'error';
                        this.form_message = 'System error... please try again later or contact support.';
                        console.error(error);
                    });
            }
        }
    }
</script>

<style scoped>
    #create-invoice-form .modal-body .form-group {
        overflow: hidden;
    }
</style>