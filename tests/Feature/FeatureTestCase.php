<?php

namespace Silentz\Charge\Tests\Feature;

use Stripe\Stripe;
use Stripe\ApiResource;
use Silentz\Charge\Tests\TestCase;
use Stripe\Exception\InvalidRequestException;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class FeatureTestCase extends TestCase
{
    /**
     * @var string
     */
    protected static $stripePrefix = 'charge-test-';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        Stripe::setApiKey(getenv('STRIPE_SECRET'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        Eloquent::unguard();

        $this->loadLaravelMigrations();

        // TODO: The migration has been added into the test, but the implementation could be broken if the real
        // migration is different from what's in here. We should find a way to reference the actual migrations.
        $this->loadMigrationsFrom(__DIR__ . '/../__migrations__');

        $this->artisan('migrate')->run();
    }

    protected static function deleteStripeResource(ApiResource $resource)
    {
        try {
            $resource->delete();
        } catch (InvalidRequestException $e) {
        }
    }
}
