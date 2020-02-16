<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\WebhookController;
use Silentz\Charge\Http\Controllers\SubscriptionController;


Route::name('charge.')->group(function () {
    Route::name('subscription.')->group(function () {
        Route::get('subscription/{name}', [SubscriptionController::class, 'show'])->name('get');
        Route::post('subscription', [SubscriptionController::class, 'store'])->name('create');
        Route::delete('subscription/{name}', [SubscriptionController::class, 'destroy'])->name('cancel');
    });

    Route::post('webhook', [WebhookController::class, 'handleWebhook'])->name('webhook');
});
