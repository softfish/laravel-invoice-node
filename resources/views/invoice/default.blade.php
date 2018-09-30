<html>
<head>
    <style>
        .billentries table {overflow: hidden;}
        .billentries table.box {border: #333 1px solid;box-sizing: border-box; padding: 10px;}
        .billentries table.box .total {text-align: right;}
        .billentries table.box .details {text-align: left; padding: 10px;}
        .billentries table.box .header {font-weight: 700; text-align: center;}
        .billentries table.box .header td { border-bottom: 1px solid #c8c8c8; padding-bottom: 10px;}
        .billentries table.box td:nth-child(2) {width: 200px;}
        .billentries table.box td {border-bottom: 1px solid #f8f8f8;}
        .billentries .entry:nth-child(even) {background-color: #333 !important;}
        .payment-method .left {border: #333 1px solid; padding: 10px; box-sizing: border-box;}
        .payment-method .right {text-align: right;}
        .payment-method .invoice-conclusion span {width: 120px; display: inline-block;}
        .payment-method h3 {margin-top: 0px;}
        .payment-method h4 {margin-top: 0px;}
        .details .total {text-align: right;}
        .details.sub {text-transform: uppercase;}
        .logo img {float: right; width: 150px;}
        .fine-print {font-size: 10px;}
    </style>
</head>
<body style="font-size: 14px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;margin: 0px auto;">
<table style="width: 100%; margin: auto;">
    <tr>
        <td>
            <div class="logo">
                @if (file_exists(public_path().'/images/invoice/invoice-logo.png'))
                    <img src="{{ asset('/images/invoice/invoice-logo.png')  }}" />
                @endif

                <div class="trading-as">
                    <label style="display: inline-block; font-weight: 700; width: 100px;">T/As</label> <span style="width: 100%;">{{ config('invoice-node.trading_as') }}</span><br />
                    <label style="display: inline-block; font-weight: 700; width: 100px;">A.B.N.</label> <span style="width: 100%;">{{ config('invoice-node.abn') }}</span><br />
                    <label style="display: inline-block; font-weight: 700; width: 100px;">Ph:</label> <span style="width: 100%;">{{ config('invoice-node.phone') }}</span><br />
                    <label style="display: inline-block; font-weight: 700; width: 100px;">e-mail:</label> <span style="width: 100%;">{{ config('invoice-node.email') }}</span>
                </div>
            </div>
        </td>
    </tr>

    <tr class="top-info" style="padding-top: 40px;">
        <td>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <div style="width: 50%;border: #333 1px solid; margin-top: 20px;padding: 20px;">
                            {{ $invoice->client_name }}<br />
                            {{ $invoice->address}}
                        </div>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <h2>Tax Invoice</h2>
                        <div><label style="display: inline-block; font-weight: 700; width: 200px;">Invoice No:</label> {{ config('invoice-node.invoice_prefix').$invoice->id }}</div>
                        <div><label style="display: inline-block; font-weight: 700; width: 200px;">Date:</label> {{ Carbon\Carbon::parse($invoice->issued_at)->format('d,M Y') }}</div>
                        <div style="font-size: 30px; font-weight: 700; margin-top: 25px; margin-bottom: 40px; color:#c8c8c8;">Total Payable: ${{ number_format($invoice->total_amount,2) }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr class="billentries" style="width: 100%;padding-top: 40px; padding-bottom: 15px;">
        <td>
            <table class="box"  style="width: 100%;">
                <tr class="header details">
                    <td>Details</td>
                    <td>Total</td>
                </tr>
                @foreach ($invoice->bill_entries as $index => $billentry)
                    <tr class="entry"
                        @if ($index%2 === 0)
                            style="background-color: #f8f8f8;"
                        @endif
                    >
                        <td class="details">{{ $billentry->description }}</td>
                        <td class="total">
                            <?= ($billentry->charge === null)? '' : '$' . '<span class="amount">'.number_format($billentry->charge, 2).'</span>' ?>
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <div>
                <table style="width: 200px; float: right;">
                    <tr class="details sub m-top">
                        <td style="font-weight: 700;">Subtotal:</td>
                        <td class="total">${{ number_format($invoice->subtotal,2)  }}</td>
                    </tr>
                    <tr class="details sub">
                        <td style="font-weight: 700;">GST:</td>
                        <td class="total">${{ number_format($invoice->tax,2) }}</td>
                    </tr>
                    <tr class="details sub">
                        <td style="font-weight: 700;">Total:</td>
                        <td class="total">${{ number_format($invoice->total_amount,2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <tr class="payment-method" style="border-top: 2px dashed #f8f8f8;">
        <td>
            <table style="width: 100%; height: 200px; margin-top: 100px; padding: 15px 5px;">
                <tr>
                    <td class="left" style="position:relative;">
                        <div>
                            <h3>How to Pay</h3>
                            <h4>Via EFT or<br> Scan the QR Code</h4>
                            <label style="display: block; font-weight: 700;">Bank:</label> <span>{{ config('invoice-node.bank_name') }}</span><br />
                            <label style="display: block; font-weight: 700;">BSB:</label> <span>{{ config('invoice-node.bsb') }}</span><br />
                            <label style="display: block; font-weight: 700;">A/C:</label> <span>{{ config('invoice-node.bank_account_number') }}</span>
                            <p class="fine-print">* IMPORTANT: Direct bank transfer might take a few day to confirm the payment.</p>
                        </div>
                        <img style="position: absolute; right:0px; top: 0px;" src="data:image/png;base64, {{ base64_encode($qrImage) }} ">
                    </td>
                    <td style="text-align: right;">
                        <table class="invoice-conclusion" style="width: 85%; float: right; margin-right: -55px; margin-top: 25px;">
                            <tr>
                                <td><label style="display: inline-block; font-weight: 700;">Ph:</label></td>
                                <td style="text-align: right;"><span>{{ config('invoice-node.phone') }}</span></td>
                            </tr>
                            <tr>
                                <td><label style="display: inline-block; font-weight: 700;">Email:</label></td>
                                <td style="text-align: right;"><span>{{ config('invoice-node.email') }}</span></td>
                            </tr>
                            <tr>
                                <td><label style="display: inline-block; font-weight: 700;">INV #:</label></td>
                                <td style="text-align: right;"><span>{{ config('invoice-node.invoice_prefix').$invoice->id }}</span></td>
                            </tr>
                            <tr>
                                <td><label style="display: inline-block; font-weight: 700;">Terms:</label></td>
                                <td style="text-align: right;"><span>7 Days PLEASE</span></td>
                            </tr>
                            <tr>
                                <td><label style="display: inline-block; font-weight: 700;">DATE:</label></td>
                                <td style="text-align: right;"><span>{{ Carbon\Carbon::parse($invoice->issued_at)->format('d,M Y') }}</span></td>
                            </tr>
                            <tr>
                                <td><label style="display: inline-block; font-weight: 700;">Amount Due:</label></td>
                                <td style="text-align: right;"><span>${{ number_format($invoice->total_amount,2) }}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>

