<?php

namespace Tests;

use Tests\Fixtures;

class RegisterFixtures
{
    public static function taxonomies(): void
    {
        register_taxonomy(Fixtures\Breeds::TAXONOMY, [], []);

        register_taxonomy(Fixtures\Countries::TAXONOMY, [], []);
    }

    public static function postTypes(): void
    {
        register_post_type(Fixtures\Family::TYPE, [
            'capability_type' => 'post',
        ]);

        register_post_type(Fixtures\Pet::TYPE, [
            'capability_type' => 'post',
        ]);
    }
}
