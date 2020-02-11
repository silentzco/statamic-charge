<?php

namespace Silentz\Charge;

use Laravel\Cashier\Cashier;
use Statamic\Facades\Permission;
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

    public function boot()
    {
        $this->bootConfig();
        $this->bootPermissions();
        $this->bootViews();
    }

    public function register()
    {
        Cashier::ignoreRoutes();
        $this->app->register(CashierServiceProvider::class);
    }

    private function bootConfig()
    {
        $this->publishes([
            __DIR__ . '../config/charge.php' => config_path('charge.php'),
        ]);
    }

    private function bootPermissions()
    {
        $this->app->booted(function () {
            Permission::register('charge')->label('Manage Charges & Subscriptions');
        });
    }

    private function bootViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'charge');
    }
}
