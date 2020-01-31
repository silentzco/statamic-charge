<?php

namespace Silentz\Charge\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
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
        return $subscription;
    }

    public function store(CreateSubscriptionRequest $request)
    {
        $request->validated();

        return Auth::user()
            ->newSubscription($request->subscription, $request->plan)
            ->create($request->payment_method);
    }

    public function destroy(Subscription $subscription, Request $request)
    {
        if ($request->cancel_immediately) {
            return $subscription->cancelNow();
        }

        return $subscription->cancel();
    }
}
