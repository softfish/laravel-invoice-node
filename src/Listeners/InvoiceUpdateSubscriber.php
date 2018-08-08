<?php

namespace Feikwok\InvoiceNode\Listeners;

use Feikwok\InvoiceNode\Events\InvoiceHasBeenIssued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class InvoiceUpdateSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function sendPaymentReceipt($event)
    {
        if ($event->invoice->status === 'paid') {
            $template = config('invoice-node.invoice_comms.invoice_receipt_template');

            Mail::send($template, ['invoice' => $event->invoice], function($m) use ($event){
                $m->to($event->invoice->email);
                $m->subject('Invoice Payment Confirmation');
            });
        }
    }

    /**
     * Sending the PDF invoice to the customer
     *
     * @param $event
     */
    public function sendInvoicePDFToCustomer($event)
    {
        if (in_array($event->invoice->status, ['issued', 'paid'])) {
            $template = config('invoice-node.invoice_comms.customer_invoice_template');
            Mail::send($template, ['invoice' => $event->invoice], function($m) use ($event){
                $m->to($event->invoice->email);
                $m->subject('Invoice '.$event->invoice->id);
                $m->attachData($event->invoice->getInvoicePdf()->output(), 'invoice-'.$event->invoice->id.'.pdf');
            });
        }
    }

    /**
     * @param $event
     */
    public function notifyAdmin($event)
    {
        $template = 'email.admin-notification';
        Mail::send($template, ['invoice' => $event->invoice], function($m) use ($event){
            $m->to(config('invoice-node.invoice_comms.admin_email'));
            $m->subject('Invalid Payment Detected for Invoice '.$event->invoice->id);
        });
    }

    /**
     * subscribe the event to it own listener(s)
     *
     * @param $event
     */
    public function subscribe($event)
    {
        $event->listen(
            'Feikwok\InvoiceNode\Events\InvoiceHasBeenIssued',
            'Feikwok\InvoiceNode\Listeners\InvoiceUpdateSubscriber@sendInvoicePDFToCustomer'
        );

        $event->listen(
            'Feikwok\InvoiceNode\Events\InvoiceHasBeenPaid',
            'Feikwok\InvoiceNode\Listeners\InvoiceUpdateSubscriber@sendPaymentReceipt'
        );

        $event->listen(
            'Feikwok\InvoiceNode\Events\InvoicePaymentCheckFailed',
            'Feikwok\InvoiceNode\Listeners\InvoiceUpdateSubscriber@notifyAdmin'
        );
    }
}
