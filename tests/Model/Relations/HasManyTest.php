<?php

namespace Tests\Worm\Model\Relations;

use Tests\Fixtures;

class HasManyTest extends \Tests\TestCase
{
    public function test_it_can_save_relations()
    {
        $family = factory(Fixtures\Family::class)->create();

        $children = factory(Fixtures\Person::class, 5)->create();

        $this->assertEmpty($family->children()->get());

        $family->children()->save($children);

        $this->assertSamePosts($family->children()->get(), $children);
    }

    public function test_it_can_save_multiple_related_items()
    {
        $family = factory(Fixtures\Family::class)->create();

        $this->assertEmpty($family->children()->get());

        for ($i = 1; $i < 15; $i++) {

            $child = factory(Fixtures\Person::class)->create();

            $family->children()->save($child);

            $this->assertEquals($family->children()->get()->count(), $i);
        }
    }

    public function test_it_can_access_relations_as_a_property()
    {
        $family = factory(Fixtures\Family::class)->create();

        $children = factory(Fixtures\Person::class, 5)->create();

        $this->assertTrue($family->children->isEmpty());

        $family->children()->save($children);

        $this->assertSamePosts($family->children, $children);

        $this->assertEquals($family->children->count(), 5);
    }
}
