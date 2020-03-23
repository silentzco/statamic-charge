<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\WebhookController;

Route::name('charge.')->group(function () {
    Route::apiResource('subscription', 'Http\Controllers\Web\SubscriptionController');

    Route::post('webhook', [WebhookController::class, 'handleWebhook'])->name('webhook');
});
