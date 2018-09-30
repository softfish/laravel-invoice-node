<div style="font-family: Raleway, sans-serif;">
    <div style="max-width: 650px; margin: 40px auto 0px; padding: 10px; overflow: hidden;background-color: #313a45;border-radius: 5px 5px 0px 0px; box-sizing: border-box;">
        <a style="text-decoration: none; color: #fff;" href="{{ url('/') }}" target="_bank">{{ config('invoice-node.invoice_comms.brand') }}</a>
    </div>
    <div class="body" style="max-width: 650px; margin: 0px auto 50px; box-sizing: border-box; padding: 20px 40px 10px; border: 1px solid #ccc; border-radius: 0px 0px 5px 5px; border-top: 0px;">
        <p>Hi admin,</p>
        <p>
            Invoice No. ({{$invoice->id}} is required your attention.
        </p>
        <p>
            Please login and action to resolve any issue.
        </p>
        <p style="text-align: right; font-size: 10px;">
            Powered by Integrated Node's Project - Invoice Node
        </p>
    </div>
</div>