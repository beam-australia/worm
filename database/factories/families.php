<?php

use Faker\Generator as Faker;
use Tests\Fixtures\Family;
use Tests\Fixtures\Person;

$factory->define(Family::class, function (Faker $faker) {
    return [
        'surname' => $faker->lastName(),
        'ethnicity' => collect(['caucasian','hispanic','mediterranean','african'])->random(),
        'member_count' => rand(1,5),
        'eye_colour' => collect(['green','blue','brown'])->random(),
    ];
});

$factory->state(Family::class, 'with-children', function (Family $instance) {

    $children = factory(Person::class, 5)->create();

    $instance->children()->save($children);

    return $instance;
});

$factory->state(Family::class, 'with-parents', function (Family $instance) {

    $mother = factory(Person::class)->create();

    $father = factory(Person::class)->create();

    $instance->mother()->save($mother);

    $instance->father()->save($father);

    return $instance;
});
