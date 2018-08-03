<div style="font-family: Raleway, sans-serif;">
    <div style="max-width: 650px; margin: 40px auto 0px; padding: 10px; overflow: hidden;background-color: #313a45;border-radius: 5px 5px 0px 0px; box-sizing: border-box;">
        <a style="text-decoration: none; color: #fff;" href="{{ url('/') }}" target="_bank">{{ config('invoice-nova.invoice_comms.brand') }}</a>
    </div>
    <div class="body" style="max-width: 650px; margin: 0px auto 50px; box-sizing: border-box; padding: 20px 40px 10px; border: 1px solid #ccc; border-radius: 0px 0px 5px 5px; border-top: 0px;">
        <p>
            Hi {{ $invoice->client_name }},
        </p>
        <p>
            Thank you for your payment. Here is your summary for the payment transaction. Please see below for more information.
        </p>
        <table style="width: 100%;">
            <thead style="background-color: #f8f8f8;">
            <tr>
                <th style="width:70%; padding: 8px;">Description</th>
                <th style="text-align: right; padding: 8px;">Charges</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->bill_entries as $billentry)
                <tr>
                    <td style="text-align: left; padding: 8px;">{{ $billentry->description }}</td>
                    <td style="text-align: right; padding: 8px;">{{ '$'.number_format($billentry->charge,2) }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f8f8f8;">
                <td style="text-align: right; padding: 8px;"><strong>Subtotal:</strong></td>
                <td style="text-align: right; padding: 8px;">{{ '$'.number_format($invoice->subtotal,2)  }}</td>
            </tr>
            <tr style="background-color: #f8f8f8;">
                <td style="text-align: right; padding: 8px;"><strong>TAX:</strong></td>
                <td style="text-align: right; padding: 8px;">{{ '$'.number_format($invoice->tax,2) }}</td>
            </tr>
            <tr style="background-color: #f8f8f8;">
                <td style="text-align: right; padding: 8px;"><strong>Total:</strong></td>
                <td style="text-align: right; padding: 8px;">{{ '$'.number_format($invoice->total_amount,2) }}</td>
            </tr>
            </tbody>
        </table>
        <p style="margin-top: 20px;">
            If you have any question please feel free to contact our support from our website.
        </p>
        <p>
            Your sincerely,
        </p>
        <p>
            Customer Support
        </p>
        <p style="text-align: right; font-size: 10px;">
            Powered by Integrated Node's Project - Invoice Nova
        </p>
    </div>
</div>