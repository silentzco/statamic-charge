<?php

namespace Silentz\Charge\Tests\Feature;

use Laravel\Cashier\Subscription;
use Silentz\Charge\Models\User;
use Silentz\Charge\Tags\Subscription as SubscriptionTag;
use Statamic\Facades\Antlers;

class TagsTest extends FeatureTestCase
{
    /** @var User */
    private $user;

    /** @var Subscription */
    private $subscription;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->subscription = Subscription::create([
            'name' => fake()->word,
            'user_id' => $this->user->id,
            'stripe_id' => fake()->md5,
            'stripe_status' => 'active',
            'stripe_plan' => fake()->md5,
            'quantity' => 1,
            'trial_ends_at' => fake()->creditCardExpirationDate,
            'ends_at' => fake()->creditCardExpirationDate,
        ]);

        $this->user->subscriptions()->save($this->subscription);
    }

    /** @test */
    public function can_cancel_subscription()
    {
        $tag = (new SubscriptionTag())
            ->setParser(Antlers::parser())
            ->setContext([])
            ->setParameters(['id' => $this->subscription->id]);

        $html = $tag->cancel();

        $this->assertStringContainsString(
            route('statamic.charge.subscriptions.destroy', [
                'subscription' => $this->subscription->id,
            ]),
            $html
        );
        $this->assertStringContainsString('_token', $html);
    }

    public function cant_get_subscription_thats_not_yours()
    {
        $user = $this->createCustomer('subscriptions_can_be_created');
        $subscription = $user
            ->newSubscription('test-subscription', static::$planId)
            ->create('pm_card_visa');

        $this
            ->actingAs($this->createCustomer('no-subscriptions'))
            ->get(route('statamic.charge.subscriptions.show', [
                'name' => $subscription->id,
            ]))->assertForbidden();
    }
}
