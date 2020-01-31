<?php

namespace Silentz\Charge\Http\Controllers;

use Auth;
use Laravel\Cashier\Subscription;
use Statamic\Http\Controllers\Controller;
use Silentz\Charge\Http\Requests\CreateSubscriptionRequest;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Subscription $subscription)
    {
        return $subscription->toArray();
    }

    public function store(CreateSubscriptionRequest $request)
    {
        $request->validated();

        Auth::user()
            ->newSubscription($request->subscription, $request->plan)
            ->create($request->payment_method);

        return 'ok';
    }
}
