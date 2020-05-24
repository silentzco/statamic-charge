<?php

namespace Silentz\Charge;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\CashierServiceProvider;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Fieldtypes\Plans;
use Silentz\Charge\Fieldtypes\Roles;
use Silentz\Charge\Listeners\HandleWebhook;
use Silentz\Charge\Tags\Subscription;
use Silentz\Charge\Tags\Subscriptions;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        Plans::class,
        Roles::class,
    ];

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

        $this->configure();
    }

    /**
     * Setup the configuration for Charge.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/charge.php', 'charge'
        );
    }

    private function bootConfig()
    {
        $this->publishes([
            __DIR__.'/../config/charge.php' => $this->app->configPath('charge.php'),
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
                ->route('charge.index')
                ->can('access charge')
                ->icon('shield-key')
                ->children([
                    'Subscriptions' => cp_route('charge.subscriptions.index'),
                    'Customers' => cp_route('charge.customers.index'),
                    'Settings' => cp_route('charge.settings.edit'),
                ]);
        });
    }

    private function bootPermissions()
    {
        $this->app->booted(function () {
            Permission::register('access charge')->label('Manage Charges & Subscriptions');
        });
    }
}
