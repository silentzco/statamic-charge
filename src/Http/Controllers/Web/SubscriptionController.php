<?php

namespace Silentz\Charge\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Subscription;
use Silentz\Charge\Http\Requests\CreateSubscriptionRequest;
use Silentz\Charge\Http\Requests\SubscriptionRequest;
use Silentz\Charge\Http\Requests\UpdateSubscriptionRequest;
use Statamic\Http\Controllers\Controller;
use Stripe\ErrorObject;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class SubscriptionController extends Controller
{
    public function store(CreateSubscriptionRequest $request): RedirectResponse
    {
        try {
            $subscription = $request->user()
                ->newSubscription($request->name)
                ->plan($request->plan, $request->quantity)
                ->create($request->payment_method);

            return $this->withSuccess($subscription, $request);
        } catch (ApiErrorException $e) {
            return $this->withErrors($e->getError(), $request);
        } catch (IncompletePayment $e) {
            return $this->withIncomplete($e->payment->id, $request);
        }
    }

    public function update(Subscription $subscription, UpdateSubscriptionRequest $request): RedirectResponse
    {
        $plan = $request->get('plan');

        try {
            if ($plan && $plan != $subscription->stripe_plan) {
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
        return $this->redirect('redirect', $request)->with([
            'charge' => [
                'success' => true,
                'subscription' => $subscription->toArray(),
            ],
        ]);
    }

    private function withErrors(ErrorObject $error, Request $request): RedirectResponse
    {
        return $this->redirect('error_redirect', $request)->withErrors($error->message, 'charge');
    }

    private function withIncomplete($id, Request $request): RedirectResponse
    {
        return $this->redirect('action_needed_redirect', $request)->with([
            'charge' => [
                'requires_action' => true,
                'action' => 'confirm',
                'payment_intent' => PaymentIntent::retrieve($id, Cashier::stripeOptions())->toArray(),
            ],
        ]);
    }

    private function redirect($field, Request $request): RedirectResponse
    {
        $redirect = $request->input($field, false);

        return $redirect ? redirect($redirect) : back();
    }
}
