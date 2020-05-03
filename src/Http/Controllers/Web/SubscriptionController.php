<?php

namespace Silentz\Charge\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use Silentz\Charge\Http\Requests\CreateSubscriptionRequest;
use Silentz\Charge\Http\Requests\SubscriptionRequest;
use Silentz\Charge\Http\Requests\UpdateSubscriptionRequest;
use Statamic\Http\Controllers\Controller;
use Stripe\ErrorObject;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    public function store(CreateSubscriptionRequest $request): RedirectResponse
    {
        try {
            $subscription = $request->user()
                ->newSubscription($request->name, $request->plan)
                ->create($request->payment_method);

            return $this->withSuccess($subscription, $request);
        } catch (ApiErrorException $e) {
            return $this->withErrors($e->getError(), $request);
        }
    }

    public function update(Subscription $subscription, UpdateSubscriptionRequest $request): RedirectResponse
    {
        $plan = $request->get('plan');

        try {
            if ($plan != $subscription->stripe_plan) {
                $subscription->swap($plan);
            }

            return $this->withSuccess(
                    $subscription->updateQuantity($request->get('quantity', 1)),
                    $request
            );
        } catch (ApiErrorException $e) {
            return $this->withErrors($e->getError(), $request);
        }
    }

    public function destroy(Subscription $subscription, SubscriptionRequest $request): RedirectResponse
    {
        try {
            if ($request->cancel_immediately) {
                $subscription->cancelNow();
            } else {
                $subscription->cancel();
            }

            return $this->withSuccess($subscription, $request);
        } catch (ApiErrorException $e) {
            return $this->withErrors($e->getError(), $request);
        }
    }

    private function withSuccess(Subscription $subscription, Request $request): RedirectResponse
    {
        $redirect = $request->input('redirect', false);

        $response = $redirect ? redirect($redirect) : back();

        return $response->with([
          'success' => true,
          'subscription' => $subscription->toArray(),
        ]);
    }

    private function withErrors(ErrorObject $error, Request $request): RedirectResponse
    {
        $redirect = $request->input('error_redirect', false);

        $response = $redirect ? redirect($redirect) : back();

        return $response->withErrors($error->message, 'charge');
    }
}
