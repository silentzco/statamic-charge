<?php

namespace Silentz\Charge\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Cashier\Subscription;
use Stripe\Subscription as StripeSubscription;

class SubscriptionFactory extends Factory
{
    private $statuses = [
        StripeSubscription::STATUS_ACTIVE,
        StripeSubscription::STATUS_CANCELED,
        StripeSubscription::STATUS_PAST_DUE,
        StripeSubscription::STATUS_TRIALING,
    ];

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->word,
            'stripe_id' => fake()->md5,
            'stripe_status' => fake()->randomElement($this->statuses),
            'stripe_plan' => fake()->md5,
            'quantity' => 1,
            'trial_ends_at' => fake()->creditCardExpirationDate,
            'ends_at' => fake()->creditCardExpirationDate,
        ];
    }
}
