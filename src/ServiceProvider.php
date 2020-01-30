<?php

namespace Silentz\Charge;

use Laravel\Cashier\CashierServiceProvider;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'actions' => __DIR__ . '/../routes/actions.php',
    ];

    public function register()
    {
        $this->app->register(CashierServiceProvider::class);
    }
}
