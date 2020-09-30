<?php

namespace Silentz\Charge\Listeners;

use Illuminate\Support\Arr;
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

        if (! $class = Arr::get($events, $event->payload['type'])) {
            return;
        }

        $class::createFromPayload($event->payload)->deliver();
    }
}
