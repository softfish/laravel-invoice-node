<div style="font-family: Raleway, sans-serif;">
    <div style="max-width: 650px; margin: 40px auto 0px; padding: 10px; overflow: hidden;background-color: #313a45;border-radius: 5px 5px 0px 0px; box-sizing: border-box;">
        <a style="text-decoration: none; color: #fff;" href="{{ url('/') }}" target="_bank">{{ config('invoice-node.invoice_comms.brand') }}</a>
    </div>
    <div class="body" style="max-width: 650px; margin: 0px auto 50px; box-sizing: border-box; padding: 20px 40px 10px; border: 1px solid #ccc; border-radius: 0px 0px 5px 5px; border-top: 0px;">
        <p>Hi admin,</p>
        <p>
            For invoice No. ({{$invoice->id}}), the customer has opt'ed to use direct bank transfer.
        </p>
        <p>
            Please follow up this transaction and make sure to mark the invoice to be paid once the funds is cleared or
            contract the customer if the transaction has any issue.
        </p>
        <p style="text-align: right; font-size: 10px;">
            Powered by Integrated Node's Project - Invoice Node
        </p>
    </div>
</div>