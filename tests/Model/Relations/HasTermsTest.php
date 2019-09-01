<?php

namespace Tests\Worm\Model\Relations;

use Tests\Fixtures;
use Tests\Fixtures\Taxonomies;

class HasTermsTest extends \Tests\TestCase
{
    public function test_it_can_save_terms()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $breeds = factory(Taxonomies\Breeds::class, 4)->create();

        $this->assertTrue($pet->breeds->isEmpty());

        $pet->breeds()->save($breeds);

        $this->assertSameTerms($pet->breeds()->get(), $breeds);
    }

    public function test_it_can_save_multiple_terms()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $this->assertTrue($pet->breeds->isEmpty());

        for ($i = 1; $i < 15; $i++) {

            $breed = factory(Taxonomies\Breeds::class)->create();

            $pet->breeds()->save($breed);

            $this->assertEquals($pet->breeds->count(), $i);
        }
    }

    public function test_it_can_sync_terms()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $breeds = factory(Taxonomies\Breeds::class, 4)->create();

        $this->assertEmpty($pet->breeds);

        $pet->breeds()->save($breeds);

        $this->assertSameTerms($pet->breeds, $breeds);

        $newBreeds = factory(Taxonomies\Breeds::class, 8)->create();

        $pet->breeds()->sync($newBreeds);

        $this->assertSameTerms($pet->breeds, $newBreeds);
    }

    public function test_it_can_access_taxonomies_as_property()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $breeds = factory(Taxonomies\Breeds::class, 4)->create();

        $pet->breeds()->save($breeds);

        $this->assertEquals($pet->breeds->count(), 4);

        $this->assertSameTerms($pet->breeds, $breeds);
    }

    public function test_it_can_return_ids()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $breeds = factory(Taxonomies\Breeds::class, 4)->create();

        $pet->breeds()->save($breeds);

        $pet->breeds()->getIds()->each(function ($id) use ($breeds) {
            $this->assertTrue($breeds->pluck('term_id')->contains($id));
        });

        $this->assertEquals($pet->breeds()->getIds()->count(), 4);
    }

    public function test_it_does_not_create_terms_when_they_dont_exist()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $breeds = factory(Taxonomies\Breeds::class, 4)->create();

        $breeds->push([
            'term_id' => 0,
            'slug' => 'should-not-exist',
            'name' => 'fake term',
        ]);

        $pet->breeds()->save($breeds);

        $slugs = $pet->breeds->pluck('slug');

        $this->assertFalse($slugs->contains('should-not-exist'));

        $this->assertFalse($slugs->contains('fake-term'));

        $this->assertEquals($slugs->count(), 4);
    }
}
