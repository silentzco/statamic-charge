<?php

namespace Silentz\Charge\Tests\Feature;

use App\User;
use Stripe\Plan;
use Stripe\Coupon;
use Stripe\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Silentz\Charge\Tests\Feature\FeatureTestCase as TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * @var string
     */
    protected static $productId;

    /**
     * @var string
     */
    protected static $planId;

    /**
     * @var string
     */
    protected static $otherPlanId;

    /**
     * @var string
     */
    protected static $premiumPlanId;

    /**
     * @var string
     */
    protected static $couponId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$productId = static::$stripePrefix . 'product-1' . Str::random(10);
        static::$planId = static::$stripePrefix . 'monthly-10-' . Str::random(10);
        static::$otherPlanId = static::$stripePrefix . 'monthly-10-' . Str::random(10);
        static::$premiumPlanId = static::$stripePrefix . 'monthly-20-premium-' . Str::random(10);
        static::$couponId = static::$stripePrefix . 'coupon-' . Str::random(10);

        Product::create([
            'id' => static::$productId,
            'name' => 'Laravel Cashier Test Product',
            'type' => 'service',
        ]);

        Plan::create([
            'id' => static::$planId,
            'nickname' => 'Monthly $10',
            'currency' => 'USD',
            'interval' => 'month',
            'billing_scheme' => 'per_unit',
            'amount' => 1000,
            'product' => static::$productId,
        ]);

        Plan::create([
            'id' => static::$otherPlanId,
            'nickname' => 'Monthly $10 Other',
            'currency' => 'USD',
            'interval' => 'month',
            'billing_scheme' => 'per_unit',
            'amount' => 1000,
            'product' => static::$productId,
        ]);

        Plan::create([
            'id' => static::$premiumPlanId,
            'nickname' => 'Monthly $20 Premium',
            'currency' => 'USD',
            'interval' => 'month',
            'billing_scheme' => 'per_unit',
            'amount' => 2000,
            'product' => static::$productId,
        ]);

        Coupon::create([
            'id' => static::$couponId,
            'duration' => 'repeating',
            'amount_off' => 500,
            'duration_in_months' => 3,
            'currency' => 'USD',
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        static::deleteStripeResource(new Plan(static::$planId));
        static::deleteStripeResource(new Plan(static::$otherPlanId));
        static::deleteStripeResource(new Plan(static::$premiumPlanId));
        static::deleteStripeResource(new Product(static::$productId));
        static::deleteStripeResource(new Coupon(static::$couponId));
    }

    /** @test */
    public function routes_exist()
    {
        $routes = Route::getRoutes();

        $this->assertTrue($routes->hasNamedRoute('statamic.charge.subscription.store'));
    }

    /** @test */
    public function checks_for_required_input()
    {
        $response = $this->post(route('statamic.charge.subscription.store'), []);

        $response->assertSessionHasErrors([
            'user_id',
            'subscription',
            'plan',
            'payment_method',
        ]);
    }

    /** @test */
    public function can_create_simple_subscription()
    {
        $user = $this->createCustomer('subscriptions_can_be_created');

        dd(User::find(1));

        $this->post(
            route('statamic.charge.subscription.store'),
            [
                'user_id' => 'subscriptions_can_be_created',
                'subscription' => 'test-subscription',
                'plan' => static::$planId,
                'payment_method' => 'pm_visa',
            ]
        )->assertOK();

        $this->assertTrue($user->subscribed('test-subscription'));
    }

    /** @test */
    public function can_call_endpoint()
    {
        $response = $this->post(route('statamic.foobar'));

        $response->assertOK();
    }
}
