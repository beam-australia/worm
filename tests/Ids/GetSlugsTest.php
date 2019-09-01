<?php

namespace Tests\Ids;

use Beam\Worm\Ids;
use Tests\Fixtures\Taxonomies;

class GetSlugsTest extends \Tests\TestCase
{
    public function test_single_term()
    {
        $term = factory(Taxonomies\Breeds::class)->create();

        $this->assertEquals(
            Ids::getSlugs($term, Taxonomies\Breeds::TAXONOMY),
            [$term->slug]
        );
    }

    public function test_single_term_id()
    {
        $term = factory(Taxonomies\Breeds::class)->create();

        $this->assertEquals(
            Ids::getSlugs((string) $term->term_id, Taxonomies\Breeds::TAXONOMY),
            [$term->slug]
        );
    }

    public function test_collection_of_terms()
    {
        $terms = factory(Taxonomies\Breeds::class, 5)->create();

        $expected = $terms
            ->pluck('slug')
            ->toArray();

        $this->assertEquals(
            Ids::getSlugs($terms, Taxonomies\Breeds::TAXONOMY),
            $expected
        );
    }

    public function test_array_of_terms()
    {
        $terms = factory(Taxonomies\Breeds::class, 5)->create();

        $expected = $terms
            ->pluck('slug')
            ->toArray();

        $this->assertEquals(
            Ids::getSlugs($terms->toArray(), Taxonomies\Breeds::TAXONOMY),
            $expected
        );
    }

    public function test_collection_of_ids()
    {
        $terms = factory(Taxonomies\Breeds::class, 5)->create();

        $ids = $terms->pluck('term_id');

        $expected = $terms
            ->pluck('slug')
            ->toArray();

        $this->assertEquals(
            Ids::getSlugs($ids, Taxonomies\Breeds::TAXONOMY),
            $expected
        );
    }

    public function test_array_of_ids()
    {
        $terms = factory(Taxonomies\Breeds::class, 5)->create();

        $ids = $terms
            ->pluck('term_id')
            ->toArray();

        $expected = $terms
            ->pluck('slug')
            ->toArray();

        $this->assertEquals(
            Ids::getSlugs($ids, Taxonomies\Breeds::TAXONOMY),
            $expected
        );
    }

    public function test_wont_return_non_existing_terms()
    {
        $terms = factory(Taxonomies\Breeds::class, 3)->create();

        $terms->push([
            'term_id' =>  28273,
            'name' =>  "fake-term",
            'slug' =>  "fake-term",
            'term_group' =>  0,
            'term_taxonomy_id' =>  23,
            'taxonomy' =>  "breeds",
            'description' =>  "",
            'parent' =>  0,
            'count' =>  0,
            'filter' =>  "raw",
        ]);

        $slugs = Ids::getSlugs($terms, Taxonomies\Breeds::TAXONOMY);

        $slugs = collect($slugs);

        $this->assertEquals($slugs->count(), 3);

        $this->assertFalse($slugs->contains("fake-term"));
    }
}
