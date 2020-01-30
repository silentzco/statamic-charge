<?php

namespace Silentz\Charge\Tests;

use Statamic\Statamic;
use Statamic\Extend\Manifest;
use Silentz\Charge\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/ExceptionHandler.php';

        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Statamic\Providers\StatamicServiceProvider::class,
            ServiceProvider::class,
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
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = [
            'assets', 'cp', 'forms', 'routes', 'static_caching',
            'sites', 'stache', 'system', 'users',
        ];

        foreach ($configs as $config) {
            $app['config']->set("statamic.$config", require(__DIR__ . "/../vendor/statamic/cms/config/{$config}.php"));
        }
    }
}
