<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Illuminate\Http\Response;
use Laravel\Cashier\Subscription;
use Statamic\Http\Controllers\CP\CpController;

class SubscriptionController extends CpController
{
    public function index()
    {
        return view('charge::cp.subscriptions', ['subscriptions' => Subscription::with('user')->get()]);
    }

    public function destroy(Subscription $subscription): Response
    {
        $subscription->cancel();

        session()->flash('success', 'Subscription Canceled');

        return response('Success');
    }
}
