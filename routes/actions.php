<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\OneTimeController;
use Silentz\Charge\Http\Controllers\SubscriptionController;

Route::name('charge.')->group(function () {
    Route::name('one-time.')->group(function () {
        Route::post('one-time', [OneTimeController::class, 'store'])->name('one-time.store');
    });

    Route::name('subscription.')->group(function () {
        Route::get('subscription/{name}', [SubscriptionController::class, 'show'])->name('show');
        Route::post('subscription', [SubscriptionController::class, 'store'])->name('store');
        Route::delete('subscription/{name}', [SubscriptionController::class, 'destroy'])->name('destroy');
    });
});
