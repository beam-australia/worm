<?php

use Faker\Generator as Faker;
use Tests\Fixtures\Family;

$factory->define(Family::class, function (Faker $faker) {
    return [
        'surname' => $faker->lastName(),
        'ethnicity' => collect(['caucasian','hispanic','mediterranean','african'])->random(),
        'member_count' => rand(1,5),
        'eye_colour' => collect(['green','blue','brown'])->random(),
    ];
});
