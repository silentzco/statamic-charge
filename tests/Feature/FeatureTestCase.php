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
