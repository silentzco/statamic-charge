<?php

use Faker\Generator as Faker;
use Silentz\Charge\Models\User;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'stripe_id' => $faker->md5,
        'card_brand' => $faker->creditCardType,
        'card_last_four' => substr($faker->creditCardNumber, -4),
        'trial_ends_at' => $faker->creditCardExpirationDate,
    ];
});
