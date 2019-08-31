<?php

namespace Tests\Ids;

use Tests\Fixtures;
use Beam\Worm\Ids;

class GetIdsTest extends \Tests\TestCase
{
    public function test_single_model()
    {
        $person = factory(Fixtures\Person::class)->create();

        $this->assertEquals(Ids::getIds($person), [$person->ID]);
    }

    public function test_single_model_id()
    {
        $person = factory(Fixtures\Person::class)->create();

        $this->assertEquals(Ids::getIds($person->ID), [$person->ID]);
    }

    public function test_collection_of_models()
    {
        $people = factory(Fixtures\Person::class, 5)->create();

        $this->assertEquals(
            Ids::getIds($people),
            $people->pluck('ID')->toArray()
        );
    }

    public function test_collection_of_objects()
    {
        $people = factory(Fixtures\Person::class, 5)
            ->create()
            ->map(function($person) {
                return (object) ['ID' => $person->ID];
            });

        $this->assertEquals(
            Ids::getIds($people),
            $people->pluck('ID')->toArray()
        );
    }

    public function test_keyed_array()
    {
        $people = factory(Fixtures\Person::class, 5)
            ->create()
            ->toArray();

        $this->assertEquals(
            Ids::getIds($people),
            collect($people)->pluck('ID')->toArray()
        );
    }

    public function test_array_of_ids()
    {
        $peopleIds = factory(Fixtures\Person::class, 5)
            ->create()
            ->pluck('ID')
            ->toArray();

        $this->assertEquals(
            Ids::getIds($peopleIds),
            $peopleIds
        );
    }
}
