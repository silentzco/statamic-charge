<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\OneTimeController;
use Silentz\Charge\Http\Controllers\SubscriptionController;

Route::post('one-time', [OneTimeController::class, 'store'])->name('charge.one-time.store');

// Subscriptions
Route::get('subscription/{subscription}', [SubscriptionController::class, 'show'])->name('charge.subscription.show');
Route::post('subscription', [SubscriptionController::class, 'store'])->name('charge.subscription.store');
Route::delete('subscription/{subscription}', [SubscriptionController::class, 'destroy'])->name('charge.subscription.destroy');
