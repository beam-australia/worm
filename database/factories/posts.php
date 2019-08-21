<?php

use Faker\Generator as Faker;
use Beam\Worm\Post;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'post_title' => $faker->sentence(5),
        'post_content' => $faker->text(180),
    ];
});
