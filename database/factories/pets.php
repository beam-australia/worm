<?php

use Faker\Generator as Faker;
use Tests\Fixtures\Pet;

$factory->define(Pet::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName(),
    ];
});
