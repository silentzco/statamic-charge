<?php

use Silentz\Charge\Models\User;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\Cp\SubscriptionController;

Route::name('charge.')->prefix('charge')->group(function () {
    Route::redirect('/', 'charge/subscriptions')->name('index');
    Route::name('subscription.')->group(function () {
        Route::view('subscriptions', 'charge::cp.subscriptions', ['subscriptions' => Subscription::with('user')->get()])
            ->name('index');
        Route::delete('subscription/{subscription}', [SubscriptionController::class, 'destroy'])
            ->name('destroy');
    });

    Route::name('customer.')->group(function () {
        Route::view('customers', 'charge::cp.customers', ['customers' => User::customers()->get()])
            ->name('index');
    });
});
