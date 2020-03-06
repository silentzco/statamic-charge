<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription;
use Illuminate\Http\RedirectResponse;
use Statamic\Http\Controllers\CP\CpController;

class SubscriptionController extends CpController
{
    public function index()
    {
        return view('charge::cp.subscriptions', ['subscriptions' => Subscription::with('user')->get()]);
    }

    public function destroy(string $name): RedirectResponse
    {
        //Cashier::findBillable('')->subscription($name)->cancel();

        return back()->with('success', 'Subscription has been canceled.');
    }
}
