<?php

namespace Silentz\Charge\Tests\Feature;

use Stripe\Plan;
use Stripe\Coupon;
use Stripe\Product;
use Statamic\Auth\User;
use Statamic\Facades\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Statamic\Facades\User as UserAPI;
use Silentz\Charge\Mail\CustomerUpdated;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Listeners\HandleWebhook;
use Silentz\Charge\Mail\CustomerSubscriptionCreated;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;
use Silentz\Charge\Mail\CustomerSubscriptionCanceled;
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

        static::$productId =
            static::$stripePrefix.'product-1'.Str::random(10);
        static::$planId =
            static::$stripePrefix.'monthly-10-'.Str::random(10);
        static::$otherPlanId =
            static::$stripePrefix.'monthly-10-'.Str::random(10);
        static::$premiumPlanId =
            static::$stripePrefix.'monthly-20-premium-'.Str::random(10);
        static::$couponId = static::$stripePrefix.'coupon-'.Str::random(10);

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

        //Role::make('foo');
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

        $this->assertTrue(
            $routes->hasNamedRoute('statamic.charge.subscription.index')
        );
        $this->assertTrue(
            $routes->hasNamedRoute('statamic.charge.subscription.store')
        );
        $this->assertTrue(
            $routes->hasNamedRoute('statamic.charge.subscription.show')
        );
        $this->assertTrue(
            $routes->hasNamedRoute('statamic.charge.subscription.update')
        );

        $this->assertTrue(
            $routes->hasNamedRoute('statamic.charge.subscription.destroy')
        );

        $this->assertTrue($routes->hasNamedRoute('statamic.charge.webhook'));
    }

    /** @test */
    public function redirected_to_login_when_logged_out()
    {
        $this->post(
            route('statamic.charge.subscription.store')
        )->assertRedirect(route('login'));
    }

    /** @test */
    public function checks_for_required_input()
    {
        $user = $this->createCustomer('subscriptions_can_be_created');

        $response = $this->actingAs($user)->post(
            route('statamic.charge.subscription.store'),
            []
        );

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
        $subscription = $user
            ->newSubscription('test-subscription', static::$planId)
            ->create('pm_card_visa');

        $this
            ->actingAs($user)
            ->get(route('statamic.charge.subscription.show', [
                'subscription' => $subscription->id,
            ]))
            ->assertOK()
            ->assertJson([
                'id' => $subscription->id,
                'name' => 'test-subscription',
                'stripe_plan' => static::$planId,
            ]);

        $this
            ->actingAs($this->createCustomer('no-subscriptions'))
            ->get(route('statamic.charge.subscription.show', [
                'name' => $subscription->id,
            ]))->assertForbidden();
    }

    /** @test */
    public function can_create_simple_subscription()
    {
        $user = $this->createCustomer('subscriptions_can_be_created');

        $this->actingAs($user)->post(route('statamic.charge.subscription.store'), [
            'name' => 'test-subscription',
            'plan' => static::$planId,
            'payment_method' => 'pm_card_visa',
        ])->assertCreated();

        $this->assertTrue($user->subscribed('test-subscription'));
    }

    /** @test */
    public function can_cancel_subscription()
    {
        $user1 = $this->createCustomer('canceled-at-end-of-period');
        $user2 = $this->createCustomer('canceled-immediately');

        $subscription1 = $user1
            ->newSubscription(
                'test-cancel-subscription-at-period-end',
                static::$planId
            )
            ->create('pm_card_visa');
        $subscription2 = $user1
            ->newSubscription(
                'test-cancel-subscription-immediately',
                static::$planId
            )
            ->create('pm_card_visa');

        $response = $this->actingAs($user1)->delete(
            route('statamic.charge.subscription.cancel', [
                'subscription' => $subscription1->id,
            ])
        );
        $response->assertOK();

        $this->assertTrue(
            $user1
                ->subscription('test-cancel-subscription-at-period-end')
                ->onGracePeriod()
        );
        $this->assertTrue(
            $user1
                ->subscription('test-cancel-subscription-at-period-end')
                ->cancelled()
        );
        $this->assertFalse(
            $user1
                ->subscription('test-cancel-subscription-immediately')
                ->onGracePeriod()
        );

        $response = $this->actingAs($user2)->delete(
            route('statamic.charge.subscription.cancel', ['subscription' => $subscription2->id]),
            ['cancel_immediately' => true]
        );
        $response->assertForbidden();
    }

    /** @test */
    public function can_edit_subscription()
    {
        $user = $this->createCustomer('edit-subscription');

        $subscription = $user
            ->newSubscription('edit-subscription', static::$planId)
            ->create('pm_card_visa');

        $response = $this->actingAs($user)->patch(
            route('statamic.charge.subscription.update', ['subscription' => $subscription->id]),
            [
                'plan' => static::$premiumPlanId,
                'quantity' => 3,
            ]
        );
        $response->assertOK();

        $subscription = $user->subscription('edit-subscription')->fresh();

        $this->assertEquals(static::$premiumPlanId, $subscription->stripe_plan);
        $this->assertEquals(3, $subscription->quantity);

        // Auth::login($user2);
        // $response = $this->delete(
        //     route('statamic.charge.subscription.cancel', [
        //         'name' => $subscription2->name
        //     ]),
        //     ['cancel_immediately' => true]
        // );
        // $response->assertForbidden();
    }

    /** @test */
    public function will_redirect_on_successful_subscription_cancellation()
    {
        $user = $this->createCustomer('canceled-at-end-of-period');

        $subscription = $user
            ->newSubscription(
                'test-cancel-subscription-at-period-end',
                static::$planId
            )
            ->create('pm_card_visa');

        $response = $this->actingAs($user)->delete(
            route('statamic.charge.subscription.cancel', [
                'subscription' => $subscription->id,
            ]),
            [
                'redirect' => '/cancel/success',
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
            'customer.subscription.created' => CustomerSubscriptionCreated::class,
            'customer.subscription.updated' => CustomerSubscriptionUpdated::class,
            'customer.subscription.canceled' => CustomerSubscriptionCanceled::class,
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
        //$subscription = $user->newSubscription('test-roles', static::$planId)->create('pm_card_visa');

        Mail::fake();
        Event::fake();

        Role::make('foo')->save();
        dd(Role::all());

        $response = $this->postJson(route('statamic.charge.webhook'), [
            'type' => 'customer.subscription.created',
        ])->assertOk();

        /** @var User */
        $statamicUser = UserAPI::fromUser($user);

        $this->assertTrue($statamicUser->hasRole('foo'));
    }
}
