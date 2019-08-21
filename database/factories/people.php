<?php

use Faker\Generator as Faker;
use Tests\Fixtures\Person;

$factory->define(Person::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'user_email' => $faker->unique()->safeEmail,
    ];
});
