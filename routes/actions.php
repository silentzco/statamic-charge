<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\OneTimeController;
use Silentz\Charge\Http\Controllers\SubscriptionController;

Route::post('one-time', [OneTimeController::class, 'store'])
    ->name('charge.one-time.store');
Route::post('subscription', [SubscriptionController::class, 'store'])
    ->name('charge.subscription.store');
