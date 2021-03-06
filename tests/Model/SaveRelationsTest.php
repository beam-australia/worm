<?php

namespace Tests\Worm\Model;

use Tests\Fixtures;

class SaveRelationsTest extends \Tests\TestCase
{
    public function test_it_saves_all_relations()
    {
        $family = factory(Fixtures\Family::class)->create();

        $this->assertEmpty($family->mother);
        $this->assertEmpty($family->father);

        $this->assertTrue($family->children->isEmpty());
        $this->assertTrue($family->pets->isEmpty());

        $models = [
            'mother' => factory(Fixtures\Person::class)->create(),
            'father' => factory(Fixtures\Person::class)->create(),
            'children' => factory(Fixtures\Person::class, 3)->create(),
            'pets' => factory(Fixtures\Pet::class, 2)->create(),
        ];

        $family->saveRelations($models);

        $this->assertSamePost($family->mother, $models['mother']);
        $this->assertSamePost($family->father, $models['father']);

        $this->assertSamePosts($family->children, $models['children']);
        $this->assertSamePosts($family->pets, $models['pets']);
    }

    public function test_it_can_save_multiple_times_relations()
    {
        $family = factory(Fixtures\Family::class)->create();

        $this->assertEmpty($family->mother);
        $this->assertEmpty($family->father);

        $this->assertTrue($family->children->isEmpty());
        $this->assertTrue($family->pets->isEmpty());

        $models = [
            'mother' => factory(Fixtures\Person::class)->create(),
            'father' => factory(Fixtures\Person::class)->create(),
            'children' => factory(Fixtures\Person::class, 3)->create(),
            'pets' => factory(Fixtures\Pet::class, 2)->create(),
        ];

        $family->saveRelations($models);

        $this->assertSamePost($family->mother, $models['mother']);
        $this->assertSamePost($family->father, $models['father']);

        $this->assertEquals($family->children->count(), 3);
        $this->assertEquals($family->pets->count(), 2);

        $models2 = [
            'mother' => factory(Fixtures\Person::class)->create(),
            'father' => factory(Fixtures\Person::class)->create(),
            'children' => factory(Fixtures\Person::class, 3)->create(),
            'pets' => factory(Fixtures\Pet::class, 2)->create(),
        ];

        $family->saveRelations($models2);

        $this->assertSamePost($family->mother, $models2['mother']);
        $this->assertSamePost($family->father, $models2['father']);

        $this->assertEquals($family->children->count(), 6);
        $this->assertEquals($family->pets->count(), 4);
    }

    public function test_it_can_save_HasOne_using_id()
    {
        $family = factory(Fixtures\Family::class)->create();

        $this->assertEmpty($family->mother);
        $this->assertEmpty($family->father);

        $models = [
            'mother_id' => factory(Fixtures\Person::class)->create()->ID,
            'father_id' => factory(Fixtures\Person::class)->create()->ID,
        ];

        $family->saveRelations($models);

        $this->assertSamePost($family->mother->ID, $models['mother_id']);
        $this->assertSamePost($family->father->ID, $models['father_id']);
    }
}
