<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\WebhookController;
use Silentz\Charge\Http\Controllers\Web\CustomerController;
use Silentz\Charge\Http\Controllers\Web\SubscriptionController;

Route::name('charge.')->group(function () {
    Route::post('webhook', [WebhookController::class, 'handleWebhook'])->name('webhook');
    Route::middleware('auth')->group(function () {
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::post('/', [SubscriptionController::class, 'store'])->name('store');
            Route::patch('{name}', [SubscriptionController::class, 'update'])->name('update');
            Route::delete('{name}', [SubscriptionController::class, 'destroy'])->name('destroy');
        });

        Route::name('customers.')->group(function () {
            Route::get('customers', [CustomerController::class, 'show'])->name('show');
            Route::patch('customers', [CustomerController::class, 'update'])->name('update');
        });
    });
});
