<div style="font-family: Raleway, sans-serif;">
    <div style="max-width: 650px; margin: 40px auto 0px; padding: 10px; overflow: hidden;background-color: #313a45;border-radius: 5px 5px 0px 0px; box-sizing: border-box;">
        <a style="text-decoration: none; color: #fff;" href="{{ url('/') }}" target="_bank">{{ config('invoice-node.invoice_comms.brand') }}</a>
    </div>
    <div class="body" style="max-width: 650px; margin: 0px auto 50px; box-sizing: border-box; padding: 20px 40px 10px; border: 1px solid #ccc; border-radius: 0px 0px 5px 5px; border-top: 0px;">
        <p>Hi {{ $invoice->client_name }},</p>
        <p>
            A new invoice has been generated for you. Please download the attachment for you PDF copy. You can also click on
            the follow link to make your secured payment.
        </p>
        <div style="width: 100%; margin: 45px 15px;">
            <a href="{{ url('innov/invoices/'.$invoice->ref.'/payment') }}" target="_blank"
               style="width: 250px; margin: auto;padding: 10px 15px;border: 1px solid; border-radius: 3px;color: #fff;text-decoration: none;background-color:#f70;"
            >
                Online Payment
            </a>
        </div>
        <p>
            Your sincerely,
        </p>
        <p>
            Customer Support
        </p>
        <p style="text-align: right; font-size: 10px;">
            Powered by Integrated Node's Project - Invoice Node
        </p>
    </div>
</div>