<?php

namespace Silentz\Charge\Http\Controllers\Web;

use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use Illuminate\Http\RedirectResponse;
use Statamic\Http\Controllers\Controller;
use Silentz\Charge\Http\Middleware\HasSubscription;
use Silentz\Charge\Http\Requests\CreateSubscriptionRequest;
use Silentz\Charge\Http\Requests\UpdateSubscriptionRequest;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(HasSubscription::class)->except('store');
    }

    public function show(Subscription $subscription): ?Subscription
    {
        return $subscription;
    }

    public function store(CreateSubscriptionRequest $request): Subscription
    {
        $request->validated();

        return current_user()
            ->newSubscription($request->name, $request->plan)
            ->create($request->payment_method);
    }

    public function update(Subscription $subscription, UpdateSubscriptionRequest $request): Subscription
    {
        $plan = $request->get('plan');

        if ($plan != $subscription->stripe_plan) {
            $subscription->swap($plan);
        }

        return $subscription->updateQuantity($request->get('quantity', 1));
    }

    public function destroy(Subscription $subscription, Request $request): RedirectResponse
    {
        $subscription = $request->cancel_immediately ? $subscription->cancelNow() : $subscription->cancel();

        return $request->redirect ? redirect($request->redirect) : back();
    }
}
