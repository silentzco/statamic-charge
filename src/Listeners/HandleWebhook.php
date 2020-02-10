<?php

namespace Silentz\Charge\Listeners;

use Illuminate\Support\Facades\Mail;
use Silentz\Charge\Mail\CustomerDeleted;
use Silentz\Charge\Mail\CustomerUpdated;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Mail\CustomerSubscriptionDeleted;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;
use Silentz\Charge\Mail\InvoicePaymentActionRequired;

class HandleWebhook
{
    public function handle(WebhookHandled $event)
    {
        $events = [
            'customer.subscription.updated' => CustomerSubscriptionUpdated::class,
            'customer.subscription.deleted' => CustomerSubscriptionDeleted::class,
            'customer.updated' => CustomerUpdated::class,
            'customer.deleted' => CustomerDeleted::class,
            'invoice.payment_action_required' => InvoicePaymentActionRequired::class,
        ];

        $class = $events[$event->payload['type']];

        // should I grab the user and the subscription here to pass to the mailables?

        Mail::to(config('charge.email.from'))->send(new $class($event->payload));
    }
}
