<?php

namespace Silentz\Charge\Listeners;

use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Events\CustomerSubscriptionUpdated;

class HandleWebhook
{
    public function handle(WebhookHandled $event)
    {
        CustomerSubscriptionUpdated::dispatch();
    }
}
