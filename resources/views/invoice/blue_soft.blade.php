<html>
<head>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat');
        @page { margin: 0px; }
        body {font-family: 'Montserrat', sans-serif; color: #444444;}
        table {width: 100%; margin: 0px; border: 0px;}
        .header-bar {background-color: #6dd0ee; color: #fff; font-size: 14px; text-align: right; padding: 5px 10px; box-sizing: border-box;}
        .header-bar tr td:first-child { text-align: left; }
        .header {border-bottom: 1px dashed #ddd;}
        .header h1 {color: #6dd0ee; font-size: 50px; text-align: center;}
        .header .trading-as { width: 100%; padding: 5px 20px; text-align: right;}
        .client-info {padding: 10px 20px; height: 180px;}
        footer h2 {color: #6dd0ee; text-align: center;}
        h3 {color: #6dd0ee;}
        .summary .total h2 {text-align: center; font-size: 60px; color: #d4d4d4; margin-top: 0px;}
        .items-list .billentries .header {background-color: #6dd0ee; color: #fff; text-align: center;}
        .items-list .billentries .header td { font-weight: 700;}
        .footer .payment-method .left {width: 55%;}
    </style>
</head>
<body>
<table class="header">
    <tr>
        <td colspan="2">
            <table class="header-bar">
                <tr>
                    <td>
                        <strong style="text-transform: uppercase">{{ config('invoice-node.trading_as') }}</strong>
                    </td>
                    <td><strong><i class="glyphicon glyphicon-asterisk"></i> PHONE:</strong> {{ config('invoice-node.phone') }}</td>
                    <td><strong>EMAIL:</strong> {{ config('invoice-node.email') }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div style="margin: 40px 10px;">
                <div class="trading-as">
                    <span style="width: 100%; text-transform: uppercase; color: #6dd0ee; font-weight: 700; font-size: 25px;  ">{{ config('invoice-node.trading_as') }}</span><br />
                    A.B.N <span style="width: 100%;">{{ config('invoice-node.abn') }}</span><br />
                    Ph: <span style="width: 100%;">{{ config('invoice-node.phone') }}</span><br />
                    Email: <span style="width: 100%;">{{ config('invoice-node.email') }}</span>
                </div>
            </div>
        </td>
        <td>
            <h1>INVOICE</h1>
        </td>
    </tr>
</table>

<table class="summary">
    <tr>
        <td>
            <div class="client-info">
                <h3>INVOICE TO</h3>
                {{ $invoice->client_name }}<br />
                {{ $invoice->address}}
            </div>
        </td>
        <td class="total">
            <div class="client-info">
                <h3>TOTAL PAYABLE</h3>
                <h2>${{ number_format($invoice->total_amount,2) }}</h2>
            </div>
        </td>
    </tr>
</table>

<table class="items-list">
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
</table>

<table class="footer" >
    <tr class="payment-method">
        <td>
            <table style="width: 100%; height: 200px; margin-top: 150px; padding: 15px; border-top: 1px dashed #ddd;">
                <tr>
                    <td class="left" style="position:relative;">
                        <div>
                            <h3>How to Pay</h3>
                            <h4>Via EFT or<br> Scan the QR Code</h4>
                            <div><strong>Bank: </strong> {{ config('invoice-node.bank_name') }}</div>
                            <div><strong>BSB:</strong> {{ config('invoice-node.bsb') }}</div>
                            <div><strong>A/C:</strong> {{ config('invoice-node.bank_account_number') }}</div>
                        </div>
                        <img style="position: absolute; right:50px; top: 0px;" src="data:image/png;base64, {{ base64_encode($qrImage) }} ">
                    </td>
                    <td style="text-align: right;">
                        <table class="invoice-conclusion">
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

<footer style="width: 100%">
    <h2>Thank you for your business</h2>
</footer>
</body>
</html>
