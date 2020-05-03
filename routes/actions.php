<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\Web\CustomerController;
use Silentz\Charge\Http\Controllers\Web\SubscriptionController;
use Silentz\Charge\Http\Controllers\WebhookController;

Route::name('charge.')->group(function () {
    Route::post('webhook', [WebhookController::class, 'handleWebhook'])->name('webhook');
    Route::middleware('auth')->group(function () {
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::post('/', [SubscriptionController::class, 'store'])->name('store');
            Route::patch('{subscription}', [SubscriptionController::class, 'update'])->name('update');
            Route::delete('{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/{user}', [CustomerController::class, 'show'])->name('show');
            Route::patch('{user}', [CustomerController::class, 'update'])->name('update');
        });
    });
});
