<?php

namespace Silentz\Charge;

use Laravel\Cashier\Cashier;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Silentz\Charge\Tags\Subscription;
use Silentz\Charge\Tags\Subscriptions;
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
        'actions' => __DIR__.'/../routes/actions.php',
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $scripts = [
        __DIR__.'/../dist/js/cp.js',
    ];

    protected $tags = [
        Subscription::class,
        Subscriptions::class,
    ];

    public function boot()
    {
        parent::boot();

        $this->bootConfig();
        $this->bootFactories();
        $this->bootNav();
        $this->bootPermissions();
    }

    public function register()
    {
        Cashier::ignoreRoutes();
        $this->app->register(CashierServiceProvider::class);
    }

    private function bootConfig()
    {
        $this->publishes([
            __DIR__.'/../config/charge.php' => config_path('charge.php'),
        ]);
    }

    private function bootFactories()
    {
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');
    }

    private function bootNav()
    {
        Nav::extend(function ($nav) {
            $nav->tools('Charge')
                ->route('charge.cp.subscriptions')
                ->can('access charge')
                ->icon('shield-key');
        });
    }

    private function bootPermissions()
    {
        $this->app->booted(function () {
            Permission::register('access charge')->label('Manage Charges & Subscriptions');
        });
    }
}
