<?php

namespace Silentz\Charge\Http\Controllers;

use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    public function __construct()
    {
        $this->middleware(VerifyWebhookSignature::class);
    }
}
