<?php

namespace Tests;

use Tests\Fixtures;
use Tests\Fixtures\Taxonomies;

class RegisterFixtures
{
    public static function taxonomies(): void
    {
        register_taxonomy(Fixtures\Taxonomies\Breeds::TAXONOMY, [], []);

        register_taxonomy(Fixtures\Taxonomies\Countries::TAXONOMY, [], []);

        register_taxonomy(Fixtures\Taxonomies\Environments::TAXONOMY, [], []);

        register_taxonomy(Fixtures\Taxonomies\Species::TAXONOMY, [], []);
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
