<?php

namespace Silentz\Charge\Tests;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Laravel\Cashier\CashierServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Silentz\Charge\ServiceProvider;
use Statamic\Extend\Manifest;
use Statamic\Providers\StatamicServiceProvider;
use Statamic\Statamic;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CashierServiceProvider::class,
            ServiceProvider::class,
            StatamicServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'silentz/charge' => [
                'id' => 'silentz/charge',
                'namespace' => 'Silentz\\Charge\\',
            ],
        ];

        $data['data']['object'] = [
            'status' => 'active',
            'cancel_at_period_end' => true,
            'current_period_end' => Carbon::now()->addDay()->timestamp,
        ];

        Arr::set($data, 'data.object.items.data.0.plan.nickname', 'Test Plan');

        config(['statamic.users.repository' => 'eloquent']);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = ['assets', 'cp', 'forms', 'routes', 'static_caching', 'sites', 'stache', 'system', 'users'];

        foreach ($configs as $config) {
            $app['config']->set("statamic.$config", require __DIR__."/../vendor/statamic/cms/config/{$config}.php");
        }
    }

    public function tearDown(): void
    {

        // destroy $app
        if ($this->app) {
            $this->callBeforeApplicationDestroyedCallbacks();

            // this is the issue.
            // $this->app->flush();

            $this->app = null;
        }

        // call the parent teardown
        parent::tearDown();
    }
}
