@extends('layouts.app')

@section('content')
    <div class="row">
        <div  id="payment-page" v-cloak>
            <div class="maskon-wrapper" v-if="maskon">
                <div class="maskon"></div>
                <div class="counter">
                    <p>Section expired soon...</p>
                    <div class="number">@{{ differentTime/1000 }}</div>
                    <p>Sec to refresh the page.</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-md-6">
                <h2>{{ 'INV'.$invoice->id }}</h2>
                <p>
                    This invoice has been issued at
                    <strong>{{ \Carbon\Carbon::parse($invoice->issued_at)->format('dS,M Y (l)') }}</strong>
                </p>
                <div class="invoice-status text-uppercase">
                    {{ $invoice->status  }}
                </div>

                @if ($invoice->status === 'paid')
                    <h3> Thank you for your payment.</h3>
                    <p>
                        Hi, look like your invoice has been paid. No payment will be required for this.
                    </p>
                @else
                    <h3>This invoice due for payment.</h3>
                    <ul>
                        <li>
                            <p>
                                For credit card payment, please use the "Pay with Card" button on the right.
                            </p>
                        </li>
                        <li>
                            <p>
                                For direct bank transfer, please use the information provided in your invoice.
                            </p>
                        </li>
                    </ul>
                    <p>
                        If you have any issue making the payment please feel free to contact us from support@integratednode.net.
                    </p>
                @endif
            </div>
            <label class="col-md-6">
                <div class="invoice-detail">
                    <h3>Invoice Summary</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-right">Charges</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->bill_entries as $billentry)
                                <tr>
                                    <td class="col-xs-8">{{ $billentry->description }}</td>
                                    <td class="col-xs-4 text-right">{{ '$'.number_format($billentry->charge,2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right"><strong>Subtotal:</strong></td>
                                <td class="text-right">{{ '$'.number_format($invoice->subtotal,2)  }}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>TAX:</strong></td>
                                <td class="text-right">{{ '$'.number_format($invoice->tax,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Total:</strong></td>
                                <td class="text-right">{{ '$'.number_format($invoice->total_amount,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if ($invoice->status === 'issued')
                    <div class="text-left col-6 float-left">
                        <form method="post" action="{{ url('/innov/invoices/'.$invoice->ref.'/banktransfer') }}">
                            <button
                                    type="button"
                                    class="btn btn-info btn-lg"
                                    data-toggle="modal"
                                    data-target="#warning-msg"
                            >Direct Bank Transfer</button>

                            <div id="warning-msg" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4>DIRECT BANK TRANSFER CONFIMRATION</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="alert alert-warning">
                                                By clicking confirm button below, you are acknowledge that you have make a direct bank transfer payment
                                                to the bank account instructed by this invoice you have received.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="btn-group btn-group-lg">
                                                <input type="submit" class="btn btn-primary" value="Confirm">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="text-right col-6 float-left">
                        <form method="post">
                            <input type="hidden" name="ref" value="{{ $invoice->ref }}">
                            <input type="hidden" name="sessionId" value="{{$sessionId}}">
                            <div class="payment-btn">
                                @if(file_exists(public_path().'/vendor/feikwok/laravel-invoice-node/images/stripepayment-logo.png'))
                                    <img  width="60px" src="{{ asset('/vendor/feikwok/laravel-invoice-node/images/stripepayment-logo.png') }}" />
                                @endif
                                <script src="https://checkout.stripe.com/checkout.js"
                                        class="stripe-button"
                                        data-key="<?= config('invoice-node.payment_gateway.stripe.api_key') ?>"
                                        data-amount="<?= number_format($invoice->total_amount,2) * 100 ?>"
                                        data-name="Payment to Invoice"
                                        data-description="Payment For Invoice ('<?= $invoice->ref ?>')"
                                        @if (false)
                                            data-image="/128x128.png"
                                        @endif
                                    >
                                </script>
                            </div>
                        </form>
                    </div>
                @elseif($invoice->status === 'paid')
                    <label class="invoice-paid-message badge badge-default">INVOICE HAS BEEN PAID. NO ACTION IS REQUIRED.</label>
                @elseif($invoice->status === 'pending payment confirmation')
                    <label class="invoice-paid-message badge badge-default">PENDING DIRECT TRANSFER PAYMENT CONFIRMATION.</label>
                    <p class="mt-5">
                        You have selected the direct bank transfer method for payment. This payment method required staff to confirm the payment
                        has been deposit the our norminated bank account.
                    </p>
                    <p class="mt-1">
                        This invoice's status will be updated to PAID once the confirmation is completed.
                    </p>
                @else
                    <div clas="col-md-12 text-center">
                        <label class="badge badge-warning">
                            INVOICE IS NOT ISSUED YET.
                        </label>
                        <p>
                            Please contact admin for more information.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        [v-cloak] > * { display:none; }
        h2,h3 {font-weight: 700;}
        .invoice-status {width: 100%; text-align: center; padding: 15px 10px; background-color: transparent; border: 1px solid; margin-top: 10px; margin-bottom: 10px;}
        .invoice-detail {margin: auto;}
        .payment-btn {float: right;}
        .invoice-paid-message {width:100%; text-align: center; padding: 15px 10px;}
        .maskon-wrapper {position: absolute; z-index: 999;}
        .maskon-wrapper .maskon {position: fixed; width: 100%; height: 100%; z-index: 1000; top:    0px; left: 0px; background-color: #333; opacity: 0.7;}
        .maskon-wrapper .counter {font-size: 20px; color: #fff; text-align: center; width: 200px; position: fixed; left: calc(50% - 100px); z-index: 1001;}
        .maskon-wrapper .counter .number {font-size: 70px; color: #e47e7a;}
    </style>
    <script src="{{ asset('vendor/feikwok/laravel-invoice-node/js/payment-page.js') }}"></script>
@endsection