<?php

use Faker\Generator as Faker;
use Beam\Worm\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'user_pass' => 'secret',
        'user_email' => $faker->unique()->safeEmail,
    ];
});
