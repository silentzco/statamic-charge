<?php

namespace Silentz\Charge\Listeners;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Mail\CustomerSubscriptionCanceled;
use Silentz\Charge\Mail\CustomerSubscriptionCreated;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;
use Silentz\Charge\Mail\CustomerUpdated;
use Silentz\Charge\Mail\InvoicePaymentActionRequired;

class HandleWebhook
{
    public function handle(WebhookHandled $event)
    {
        $events = [
            'customer.subscription.created' => CustomerSubscriptionCreated::class,
            'customer.subscription.updated' => CustomerSubscriptionUpdated::class,
            'customer.subscription.deleted' => CustomerSubscriptionCanceled::class,
            'customer.updated' => CustomerUpdated::class,
            'invoice.payment_action_required' => InvoicePaymentActionRequired::class,
        ];

        $type = $event->payload['type'];

        if (! $class = Arr::get($events, $type)) {
            return;
        }

        // should I grab the user and the subscription here to pass to the mailables?

        Mail::to(config('charge.email.from'))->send(new $class($event->payload));
    }
}
