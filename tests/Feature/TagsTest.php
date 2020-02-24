<?php

namespace Silentz\Charge\Tests\Feature;

use Statamic\Facades\Antlers;
use Silentz\Charge\Models\User;
use Laravel\Cashier\Subscription;
use Silentz\Charge\Tests\TestCase;
use Silentz\Charge\Tags\Subscription as SubscriptionTag;

class TagsTest extends FeatureTestCase
{
    /** @var User */
    private $user;

    /** @var Subscription */
    private $subscription;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/database/factories');

        $this->user = factory(User::class)->create();

        $this->subscription = factory(Subscription::class)->make([
            'stripe_status' => 'active'
        ]);

        $this->user->subscriptions()->save($this->subscription);
    }

    /** @test */
    public function can_cancel_subscription()
    {
        $tag = (new SubscriptionTag())
            ->setParser(Antlers::parser())
            ->setContext([])
            ->setParameters(['name' => $this->subscription->name]);

        $html = $tag->cancel();

        $this->assertStringContainsString(
            route('statamic.charge.subscription.cancel', [
                'name' => $this->subscription->name
            ]),
            $html
        );
        $this->assertStringContainsString('_token', $html);
    }
}
