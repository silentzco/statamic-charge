<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\Cp\SubscriptionController;

Route::name('charge.cp.')->prefix('charge')->group(function () {
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
    Route::name('subscription.')->group(function () {
        Route::get('subscription/{name}/delete', [SubscriptionController::class, 'destroy'])->name('cancel');
    });
});
