<?php

namespace Silentz\Charge\Listeners;

use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;

class HandleWebhook
{
    public function handle(WebhookHandled $event)
    {
        // send email in here, no need for other Events

        // get the right mailable, pass the payload in

        Mail::to('foo@bar.com')->send(new CustomerSubscriptionUpdated($event->payload));
    }
}
