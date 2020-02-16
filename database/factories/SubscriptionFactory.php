<?php

use Faker\Generator as Faker;
use Laravel\Cashier\Subscription;
use Stripe\Subscription as StripeSubscription;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$statuses = [
    StripeSubscription::STATUS_ACTIVE,
    StripeSubscription::STATUS_CANCELED,
    StripeSubscription::STATUS_PAST_DUE,
    StripeSubscription::STATUS_TRIALING,
];

$factory->define(Subscription::class, function (Faker $faker) use ($statuses) {
    return [
        'name' => $faker->word,
        'stripe_id' => $faker->md5,
        'stripe_status' => $faker->randomElement($statuses),
        'stripe_plan' => $faker->md5,
        'quantity' => 1,
        'trial_ends_at' => $faker->creditCardExpirationDate,
        'ends_at' => $faker->creditCardExpirationDate,
    ];
});
