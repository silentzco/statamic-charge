<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\WebhookController;
use Silentz\Charge\Http\Controllers\Web\SubscriptionController;

Route::name('charge.')->group(function () {
    Route::post('webhook', [WebhookController::class, 'handleWebhook'])->name('webhook');
    Route::middleware('auth')->group(function () {
        Route::name('subscription.')->group(function () {
            Route::get('subscription/{subscription}', [SubscriptionController::class, 'show'])->name('get');
            Route::post('subscription', [SubscriptionController::class, 'store'])->name('store');
            Route::patch('subscription/{subscription}', [SubscriptionController::class, 'update'])->name('update');
            Route::delete('subscription/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
        });
    });
});
