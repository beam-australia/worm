<?php

namespace Tests\Worm\Model;

use WP_Term;
use Tests\Fixtures;
use Tests\Fixtures\Taxonomies;

class ToArrayTest extends \Tests\TestCase
{
    public function test_it_returns_attributes()
    {
        $family = factory(Fixtures\Family::class)->create();

        $asArray = $family->toArray();

        foreach ($family->attributes as $attribute) {
            $this->assertEquals($asArray[$attribute], $family->$attribute);
        }
    }

    public function test_it_returns_taxonomies()
    {
        $family = factory(Fixtures\Family::class)->create();

        $countries = factory(Taxonomies\Countries::class, 10)->create();

        $family->countries()->save($countries);

        $asArray = $family->toArray();

        $this->assertSameTerms($asArray['countries'], $countries);
    }

    public function test_it_returns_taxonomies_empty_taxonomies()
    {
        $family = factory(Fixtures\Family::class)->create();

        $this->assertTrue($family->countries->isEmpty());

        $asArray = $family->toArray();

        $this->assertEmpty($asArray['countries']);
    }

    public function test_it_returns_HASONE_model_relations()
    {
        $family = factory(Fixtures\Family::class)->create();

        $mother = factory(Fixtures\Person::class)->create();

        $family->mother()->save($mother);

        $asArray = $family->toArray();

        $this->assertSamePost($asArray['mother'], $mother);

        $this->assertEquals($asArray['mother'], $mother->toArray());

        $this->assertEquals($asArray['mother_id'], (string) $mother->ID);
    }

    public function test_it_returns_HASMANY_model_relations()
    {
        $family = factory(Fixtures\Family::class)->create();

        $children = factory(Fixtures\Person::class, 10)->create();

        $family->children()->save($children);

        $asArray = $family->toArray();

        $this->assertSamePosts($asArray['children'], $children);
    }

    public function test_it_can_key_relations()
    {
        $family = factory(Fixtures\Family::class)->create();

        $children = factory(Fixtures\Person::class)->create();

        $family->children()->save($children);

        $asArray = $family->toArray();

        foreach ($asArray['children'] as $userEmail => $child) {
            $this->assertEquals($userEmail, $child['user_email']);
        }
    }
}
