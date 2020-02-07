<?php

namespace Silentz\Charge;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\CashierServiceProvider;
use Silentz\Charge\Listeners\HandleWebhook;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $listen = [
        WebhookHandled::class => [HandleWebhook::class],
    ];

    protected $routes = [
        'actions' => __DIR__ . '/../routes/actions.php',
    ];

    public function register()
    {
        Cashier::ignoreRoutes();
        $this->app->register(CashierServiceProvider::class);
    }
}
