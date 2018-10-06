<?php

namespace Feikwok\InvoiceNode\Mail;

use Feikwok\InvoiceNode\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoicePaidWithBankTransfer extends Mailable
{
    use Queueable, SerializesModels;

    private $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment With Direct Transfer Notification')
                    ->view('invoice-node::email.banktransfer-notification', ['invoice' => $this->invoice]);
    }
}
