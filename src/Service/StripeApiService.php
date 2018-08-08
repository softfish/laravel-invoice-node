<?php
namespace Feikwok\InvoiceNode\Services ;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class StripeApiService
{
    public function __construct()
    {
        Stripe::setApiKey(config('invoice-node.payment_gateway.stripe.pkey'));
    }

    public function validatePayment(array $data)
    {
        try {
            if (isset($data['stripeToken'])) {

                $tokenJson = \Stripe\Token::retrieve($data['stripeToken']);

                if (isset($tokenJson->card) and !empty($tokenJson->card)) {
                    // the token creation must be with in 15 mins.
                    if (!Carbon::createFromTimestamp($tokenJson->created)->lessThanOrEqualTo(Carbon::now('UTC')->subMinutes(15))) {
                        return true;
                    }
                }
            }
        } catch( \Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }
}