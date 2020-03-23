<?php

use Illuminate\Support\Facades\Route;

Route::name('charge.')->prefix('charge')->group(function () {
    Route::apiResource('subscription', 'Http\Controllers\Cp\SubscriptionController');
});
