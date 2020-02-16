<?php

namespace Silentz\Charge\Tests\Feature;

use Stripe\Plan;
use Stripe\Coupon;
use Stripe\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Silentz\Charge\Mail\CustomerUpdated;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Listeners\HandleWebhook;
use Silentz\Charge\Mail\CustomerSubscriptionDeleted;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;
use Silentz\Charge\Mail\InvoicePaymentActionRequired;
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

        $this->assertTrue($routes->hasNamedRoute('statamic.charge.subscription.get'));
        $this->assertTrue($routes->hasNamedRoute('statamic.charge.subscription.create'));
        $this->assertTrue($routes->hasNamedRoute('statamic.charge.subscription.cancel'));
        $this->assertTrue($routes->hasNamedRoute('statamic.charge.webhook'));
    }

    /** @test */
    public function redirected_to_login_when_logged_out()
    {
        $this
            ->post(route('statamic.charge.subscription.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function checks_for_required_input()
    {
        $user = $this->createCustomer('subscriptions_can_be_created');

        Auth::login($user);

        $response = $this->post(route('statamic.charge.subscription.create'), []);

        $response->assertSessionHasErrors([
            'subscription',
            'plan',
            'payment_method',
        ]);
    }

    /** @test */
    public function can_get_subscription()
    {
        $user = $this->createCustomer('subscriptions_can_be_created');
        $subscription = $user->newSubscription('test-subscription', static::$planId)->create('pm_card_visa');

        Auth::login($user);

        $this->get(route('statamic.charge.subscription.get', ['name' => 'test-subscription']))
            ->assertOK()
            ->assertJson(
                [
                    'id' => $subscription->id,
                    'name' => 'test-subscription',
                    'stripe_plan' => static::$planId,
                ]
            );

        Auth::login($this->createCustomer('no-subscriptions'));

        $this->get(route('statamic.charge.subscription.get', ['name' => 'test-subscription']))
            ->assertForbidden();
    }

    /** @test */
    public function can_create_simple_subscription()
    {
        $user = $this->createCustomer('subscriptions_can_be_created');

        Auth::login($user);

        $this->post(
            route('statamic.charge.subscription.create'),
            [
                'subscription' => 'test-subscription',
                'plan' => static::$planId,
                'payment_method' => 'pm_card_visa',
            ]
        )->assertCreated();

        $this->assertTrue($user->subscribed('test-subscription'));
    }

    /** @test */
    public function can_cancel_subscription()
    {
        $user1 = $this->createCustomer('canceled-at-end-of-period');
        $user2 = $this->createCustomer('canceled-immediately');

        $subscription1 = $user1->newSubscription('test-cancel-subscription-at-period-end', static::$planId)->create('pm_card_visa');
        $subscription2 = $user1->newSubscription('test-cancel-subscription-immediately', static::$planId)->create('pm_card_visa');

        Auth::login($user1);

        $response = $this->delete(route('statamic.charge.subscription.cancel', ['name' => $subscription1->name]));
        $response->assertOK();

        $this->assertTrue($user1->subscription('test-cancel-subscription-at-period-end')->onGracePeriod());
        $this->assertTrue($user1->subscription('test-cancel-subscription-at-period-end')->cancelled());
        $this->assertFalse($user1->subscription('test-cancel-subscription-immediately')->onGracePeriod());

        Auth::login($user2);
        $response = $this->delete(
            route('statamic.charge.subscription.cancel', ['name' => $subscription2->name]),
            ['cancel_immediately' => true]
        );
        $response->assertForbidden();
    }

    /** @test */
    public function will_redirect_on_successful_subscription_cancellation()
    {
        $user = $this->createCustomer('canceled-at-end-of-period');

        $subscription = $user->newSubscription('test-cancel-subscription-at-period-end', static::$planId)->create('pm_card_visa');

        $response = $this
            ->actingAs($user)
            ->delete(
                route('statamic.charge.subscription.cancel', ['name' => $subscription->name]),
                [
                    'redirect' => '/cancel/success'
                ]
            );

        $response->assertRedirect('/cancel/success');
    }
    /** @test */
    public function does_respond_to_events()
    {
        $this->mock(HandleWebhook::class, function ($mock) {
            $mock->shouldReceive('handle')->once();
        });

        WebhookHandled::dispatch([]);
    }

    /** @test */
    public function events_do_send_email()
    {
        Mail::fake();

        $types = [
            'customer.subscription.updated' => CustomerSubscriptionUpdated::class,
            'customer.subscription.deleted' => CustomerSubscriptionDeleted::class,
            'customer.updated' => CustomerUpdated::class,
            'invoice.payment_action_required' => InvoicePaymentActionRequired::class,
        ];

        foreach ($types as $type => $class) {
            WebhookHandled::dispatch(['type' => $type]);

            Mail::assertSent($class);
        }
    }

    /** @test */
    public function adds_roles_when_subscription_created()
    {
        $user = $this->createCustomer('add-roles');

        $subscription = $user->newSubscription('add-roles', static::$planId)->create('pm_card_visa');
    }
}
