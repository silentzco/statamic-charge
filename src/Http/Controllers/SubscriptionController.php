<?php

namespace Silentz\Charge\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController
{
    public function store(Request $request)
    {
        $request->validate([
            'subscription' => 'required',
            'plan' => 'required',
            'payment_method' => 'required',
        ]);

        return 'ok';
    }
}