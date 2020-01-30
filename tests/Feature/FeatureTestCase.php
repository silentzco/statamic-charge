<?php

namespace Silentz\Charge\Tests\Feature;

use Stripe\Stripe;
use Stripe\ApiResource;
use Silentz\Charge\Tests\TestCase;
use Silentz\Charge\Tests\Fixtures\User;
use Stripe\Exception\InvalidRequestException;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class FeatureTestCase extends TestCase
{
    /**
     * @var string
     */
    protected static $stripePrefix = 'cashier-test-';

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

    protected function createCustomer($description = 'erin'): User
    {
        return User::create([
            'email' => "{$description}@cashier-test.com",
            'name' => 'Erin Dalzell',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);
    }
}
