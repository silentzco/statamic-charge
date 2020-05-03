<?php

use Illuminate\Support\Facades\Route;
use Silentz\Charge\Http\Controllers\Cp\SubscriptionController;
use Silentz\Charge\Http\Controllers\Web\CustomerController;

Route::name('charge.')->prefix('charge')->group(function () {
    Route::redirect('/', 'charge/subscriptions')->name('index');
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::delete('/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
    });
});
