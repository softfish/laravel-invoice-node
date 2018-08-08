@extends('layouts.app')

@section('content')
    <div class="row" id="edit-invoice-app" data-jsoninvoiceData="{{json_encode($invoice)}}" v-cloak>
        <div class="maskon-wrapper" v-if="maskon">
            <div class="maskon"></div>
            @if (file_exists(public_path().'/images/loading.gif'))
                <img class="loading" src="{{ asset('images/loading.gif') }}"/>
            @else
                <h3 class="loading">Loading...</h3>
            @endif
        </div>
        <div class="container">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/innov/invoices') }}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                </ol>
            </nav>

            @include('flash::message')

            <div class="alert text-center" :class="{'alert-info': (alert.type != 'error'), 'alert-warning': (alert.type === 'error')}"
                 v-if="alert.message != null"
                 v-on:click="removeMessage()"
            >
                @{{ alert.message }}
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>
                            Info.
                            <a class="float-right btn btn-primary" target="_blank" href="{{ url('/innov/invoices/'.$invoice->id.'/preview')}}">
                                <i class="glyphicon glyphicon-search"></i> Preview
                            </a>
                        </h3>
                        <small class="text-muted text-right visible-lg-block"><strong>Ref:</strong> {{ $invoice->ref }}</small>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>Client Name</td>
                                <td>
                                    <input class="form-control" v-model="invoice.client_name"
                                           :disabled="(!invoice.is_editable)"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Business Name</td>
                                <td>
                                    <input class="form-control" v-model="invoice.business_name"
                                           :disabled="(!invoice.is_editable)"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Business Number</td>
                                <td>
                                    <input class="form-control" v-model="invoice.business_number"
                                           :disabled="(!invoice.is_editable)"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Email Contact</td>
                                <td>
                                    <input class="form-control" type='email' v-model="invoice.email"
                                           :disabled="(!invoice.is_editable)"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Phone Contact</td>
                                <td>
                                    <input class="form-control" v-model="invoice.phone"
                                           :disabled="(!invoice.is_editable)"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Tax</td>
                                <td>
                                    <input class="form-control" type='number' v-model="invoice.tax_rate"
                                           :disabled="(!invoice.is_editable)"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Status </td>
                                <td class="text-uppercase">
                                    <strong>@{{ invoice.status }}</strong>
                                    <div class="text-muted" v-if="invoice.status === 'issued'">
                                        @{{ invoice.issued_at | moment('Do, MMM YYYY') }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Template</td>
                                <td>
                                    <select class="form-control text-capitalize" v-model="invoice.template"
                                            :disabled="(!invoice.is_editable)"
                                    >
                                        @foreach($invoice->loadAvailableTemplates() as $template_name)
                                            <option value="{{$template_name}}">{{ str_replace('_', ' ', $template_name) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <div class="btn"
                                             :class="{'btn-primary': (invoice.is_editable), 'btn-default': !invoice.is_editable}"
                                             :disabled="(!invoice.is_editable)"
                                             v-on:click="updateInvoiceAttributes()"
                                        >
                                            UPDATE
                                        </div>
                                        <div class="btn"
                                             :class="{'btn-primary':(invoice.status === 'issued'), 'btn-default': (invoice.status != 'issued')}"
                                             v-on:click="issueInvoice()"
                                             :disabled="(!invoice.is_editable)"
                                        >
                                            ISSUE
                                        </div>
                                        <div class="btn"
                                             :class="{'btn-primary':(invoice.status === 'cancelled'), 'btn-default': (invoice.status != 'cancelled')}"
                                             :disabled="(!invoice.is_editable)"
                                             v-on:click="cancelInvoice()"
                                        >
                                            CANCELLED
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if (in_array($invoice->status, ['issued', 'paid']))
                        <div class="panel-footer">
                            <div class="btn-group">
                                <a class="float-right btn btn-default" href="{{ url('/innov/invoices/'.$invoice->id.'/resend?event=invoice-issued')}}">
                                    <i class="glyphicon glyphicon-envelope"></i> Resend Invoice to Customer
                                </a>
                                @if ($invoice->status === 'paid')
                                    <a class="float-right btn btn-default" href="{{ url('/innov/invoices/'.$invoice->id.'/resend?event=payment-confirmed')}}">
                                        <i class="glyphicon glyphicon-envelope"></i> Resend Payment Confirmation Email
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>
                            Bill Entries
                            <a class="float-right btn btn-primary" target="_blank" href="{{ url('/innov/invoices/'.$invoice->ref.'/payment') }}">
                                <i class="glyphicon glyphicon-credit-card"></i>
                                Make a Payment
                            </a>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12 text-center text-muted" v-if="invoice.bill_entries.length < 1">
                            No bill item has been created yet.
                        </div>
                        <table class="table table-striped" v-if="invoice.bill_entries.length > 0">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <transition name="fade">
                                    <tr v-for="billentry in invoice.bill_entries">
                                        <td>@{{ billentry.description }}</td>
                                        <td
                                            v-if="(billentry.charge != null)"
                                        >$@{{ billentry.charge.toFixed(2) }}</td>
                                        <td v-if="(billentry.charge === null)">&nbsp;</td>
                                        <td class="text-right">
                                            <i class="glyphicon glyphicon-remove"
                                               v-on:click="removeExistingBillEntry(billentry.id)"
                                               v-if="invoice.is_editable"
                                            ></i>
                                        </td>
                                    </tr>
                                </transition>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <div class="text-center" v-if="!invoice.is_editable">
                            Invoice has been issued.
                        </div>
                        <div class="row" v-if="invoice.is_editable">
                            <div class="col-md-6">
                                <input class="form-control" v-model="beForm.description" placeholder="Bill Entry Description">
                            </div>
                            <div class="col-md-3">
                                <input type="number" min="0.01" class="form-control" v-model="beForm.charge" placeholder="Charge">
                            </div>
                            <div class="col-md-3">
                                <button class="btn-sm form-control btn-primary" v-on:click="createNewBillEntry()">ADD +</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="{{ asset('vendor/feikwok/laravel-invoice-node/js/edit-invoice.js') }}"></script>
<style>
    [v-cloak] {display: none}
    .float-left {float: left;}
    .float-right {float: right;}
    h3 { margin-bottom: 15px; overflow: hidden;}
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
    select.form-control {height: 34px !important;}
    .maskon-wrapper { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999; background-color: #333; opacity: 0.7;}
    .maskon {z-index: 1000; width: 100%; height: 100%;}
    .maskon-wrapper .loading {z-index: 1001; position: fixed; top: calc(40%); left: calc(50% - 100px); width: 200px;}
</style>

@endsection