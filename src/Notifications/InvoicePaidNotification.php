<?php

namespace Feikwok\InvoiceNode\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoicePaidNotification extends Notification
{
    use Queueable;

    private $invoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $methods =  ['mail'];

        if (config('invoice-node.notification.slack.billing_webhook') != null) {
            $methods[] = 'slack';
        }

        return $methods;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Invoice '.$this->invoice.', total: $'.number_format($invoice->total_amount,2).' has been paid.');
    }

    /**
     * Get the Slack representation of the notification
     *
     * @param $notifiable
     * @return $this
     */
    public function toSlack($notifiable)
    {
        $notifiable->slack_webhook_url = config('invoice-node.notification.slack.billing_webhook');

        return (new SlackMessage)
                ->content('Invoice '.$this->invoice.', total: $'.number_format($invoice->total_amount,2).' has been paid.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
