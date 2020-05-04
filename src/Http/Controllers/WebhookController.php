<?php

namespace Silentz\Charge\Http\Controllers;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Statamic\Auth\User as AuthUser;
use Statamic\Facades\User;
use Statamic\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierController
{
    protected function handleCustomerSubscriptionCreated(array $payload): Response
    {
        /** @var AuthUser */
        $user = User::fromUser(Cashier::findBillable($payload['customer']));
        $plan = Arr::get($payload, 'data.object.items.data.0.plan.id');

        $rolePlan = collect(config('charge.subscription.roles'))->firstWhere('plan', $plan);

        $user->assignRole($rolePlan['role'])->save();

        return $this->successMethod();
    }
}
