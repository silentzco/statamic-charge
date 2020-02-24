<?php

namespace Silentz\Charge\Tests;

use Carbon\Carbon;
use Statamic\Statamic;
use Illuminate\Support\Arr;
use Statamic\Extend\Manifest;
use Silentz\Charge\Models\User;
use Silentz\Charge\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/ExceptionHandler.php';

        parent::setUp();

        //        $this->withFactories(__DIR__ . '/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [\Statamic\Providers\StatamicServiceProvider::class, ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'silentz/charge' => [
                'id' => 'silentz/charge',
                'namespace' => 'Silentz\\Charge\\'
            ]
        ];

        Route::get('/login')->name('login');

        $data['data']['object'] = [
            'status' => 'active',
            'cancel_at_period_end' => true,
            'current_period_end' => Carbon::now()->addDay()->timestamp
        ];

        Arr::set($data, 'data.object.items.data.0.plan.nickname', 'Test Plan');

        Route::get('/csu', function () use ($data) {
            return new CustomerSubscriptionUpdated($data);
        });

        config(['statamic.users.repository' => 'eloquent']);

        Statamic::pushActionRoutes(function () {
            return require_once realpath(__DIR__ . '/../routes/actions.php');
        });
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = ['assets', 'cp', 'forms', 'routes', 'static_caching', 'sites', 'stache', 'system', 'users'];

        foreach ($configs as $config) {
            $app['config']->set("statamic.$config", require __DIR__ . "/../vendor/statamic/cms/config/{$config}.php");
        }
    }

    protected function createCustomer($description = 'erin'): User
    {
        return User::create([
            'email' => "{$description}@cashier-test.com",
            'name' => 'Erin Dalzell',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);
    }
}
