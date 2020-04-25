<?php

namespace Silentz\Charge\Http\Controllers\Web;

use Stripe\ErrorObject;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use Illuminate\Http\RedirectResponse;
use Stripe\Exception\ApiErrorException;
use Statamic\Http\Controllers\Controller;
use Silentz\Charge\Http\Requests\CancelSubscriptionRequest;
use Silentz\Charge\Http\Requests\CreateSubscriptionRequest;
use Silentz\Charge\Http\Requests\UpdateSubscriptionRequest;

class SubscriptionController extends Controller
{
    public function store(CreateSubscriptionRequest $request): RedirectResponse
    {
        $request->validated();

        try {
            $subscription = current_user()
              ->newSubscription($request->name, $request->plan)
              ->create($request->payment_method);

            return $this->withSuccess($subscription, $request);
        } catch (ApiErrorException $e) {
            return $this->withErrors($e->getError(), 'create');
        }
    }

    public function update(Subscription $subscription, UpdateSubscriptionRequest $request): RedirectResponse
    {
        $request->validated();

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
            return $this->withErrors($e->getError(), 'update');
        }
    }

    public function destroy(Subscription $subscription, CancelSubscriptionRequest $request): RedirectResponse
    {
        try {
            $subscription = $request->cancel_immediately ? $subscription->cancelNow() : $subscription->cancel();

            return $this->withSuccess($subscription, $request);
        } catch (ApiErrorException $e) {
            return $this->withErrors($e->getError(), 'cancel');
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

    private function withErrors(ErrorObject $error, string $bag): RedirectResponse
    {
        return back()->withErrors([
            'type' => $error->type,
            'code' => $error->code,
            'param' => $error->param,
            'message' => $error->message,
        ], $bag);
    }
}
