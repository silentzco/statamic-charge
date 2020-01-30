<?php

namespace Silentz\Charge\Http\Controllers;

use Silentz\Charge\Http\Requests\CreateSubscriptionRequest;

class SubscriptionController
{
    public function store(CreateSubscriptionRequest $request)
    {
        $request->validated();

        $request
            ->getUser()
            ->newSubscription($request->subscriptions, $request->plan)
            ->create($request->payment_method);

        return 'ok';
    }
}