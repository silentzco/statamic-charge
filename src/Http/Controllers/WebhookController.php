<?php

namespace Silentz\Charge\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Statamic\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierController
{
    protected function handleCustomerSubscriptionCreated(array $payload): Response
    {
        $this
            ->getUserByStripeId(Arr::get($payload, 'data.object.customer'))
            ->switchToPlan(Arr::get($payload, 'data.object.items.data.0.price.id'));

        return $this->successMethod();
    }

    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        $response = parent::handleCustomerSubscriptionUpdated($payload);

        optional($this->getUserByStripeId(Arr::get($payload, 'data.object.customer')))
            ->switchToPlan(Arr::get($payload, 'data.object.plan.id'));

        return $response;
    }
}
