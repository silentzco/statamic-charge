<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Laravel\Cashier\Subscription;
use Illuminate\Http\RedirectResponse;
use Statamic\Http\Controllers\CP\CpController;

class SubscriptionController extends CpController
{
    public function index()
    {
        return view('charge::cp.subscriptions', ['subscriptions' => Subscription::with('user')->get()]);
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->cancel();

        return back()->with('success', 'Subscription has been canceled.');
    }
}
