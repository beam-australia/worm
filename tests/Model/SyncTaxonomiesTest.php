<?php

namespace Tests\Worm\Model;

use Tests\Fixtures\Pet;
use Tests\Fixtures\Taxonomies;

class SyncTaxonomiesTest extends \Tests\TestCase
{
    public function test_it_syncs_all_taxonomies()
    {
        $pet = factory(Pet::class)->create();

        $this->assertTrue($pet->breeds->isEmpty());
        $this->assertTrue($pet->environments->isEmpty());
        $this->assertTrue($pet->species->isEmpty());

        $terms = [
            'breeds' => factory(Taxonomies\Breeds::class, 5)->create(),
            'environments' => factory(Taxonomies\Environments::class, 5)->create(),
            'species' => factory(Taxonomies\Species::class, 5)->create(),
        ];

        $pet->syncTaxonomies($terms);

        $this->assertSameTerms($pet->breeds, $terms['breeds']);
        $this->assertSameTerms($pet->environments, $terms['environments']);
        $this->assertSameTerms($pet->species, $terms['species']);
    }

    public function test_it_clears_previous_terms()
    {
        $pet = factory(Pet::class)->create();

        for ($i = 0; $i < 3; $i++) {

            $terms = [
                'breeds' => factory(Taxonomies\Breeds::class, 5)->create(),
                'environments' => factory(Taxonomies\Environments::class, 5)->create(),
                'species' => factory(Taxonomies\Species::class, 5)->create(),
            ];

            $pet->syncTaxonomies($terms);
        }

        $this->assertEquals($pet->breeds->count(), 5);
        $this->assertEquals($pet->environments->count(), 5);
        $this->assertEquals($pet->species->count(), 5);
    }
}
