<?php

namespace Silentz\Charge\Tests\Feature;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Silentz\Charge\Models\User;
use Silentz\Charge\Tests\TestCase;
use Stripe\ApiResource;
use Stripe\Exception\InvalidRequestException;
use Stripe\Stripe;

abstract class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    protected static $stripePrefix = 'charge-test-';

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    protected function defineRoutes($router)
    {
        $router->get('/login')->name('login');
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        Stripe::setApiKey(getenv('STRIPE_SECRET'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        Eloquent::unguard();
    }

    protected function createCustomer($description = 'erin'): User
    {
        return User::create([
            'email' => "{$description}@cashier-test.com",
            'name' => 'Erin Dalzell',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'stripe_id' => 'cus_HaZNLvZsFWfLwp',
        ]);
    }

    protected static function deleteStripeResource(ApiResource $resource)
    {
        try {
            $resource->delete();
        } catch (InvalidRequestException $e) {
        }
    }
}
