<?php

namespace Tests\Ids;

use Tests\Fixtures;
use Beam\Worm\Ids;

class GetIdTest extends \Tests\TestCase
{
    public function test_single_model()
    {
        $person = factory(Fixtures\Person::class)->create();

        $this->assertEquals(Ids::getId($person), $person->ID);
    }

    public function test_collection_of_models()
    {
        $person = factory(Fixtures\Person::class, 2)->create();

        $this->assertEquals(Ids::getId($person), $person[0]->ID);
    }

    public function test_array_of_models()
    {
        $person = factory(Fixtures\Person::class, 2)
            ->create()
            ->toArray();

        $this->assertEquals(Ids::getId($person), $person[0]['ID']);
    }

    public function test_array_of_ids()
    {
        $person = factory(Fixtures\Person::class, 2)
            ->create()
            ->pluck('ID')
            ->toArray();

        $this->assertEquals(Ids::getId($person), $person[0]);
    }

    public function test_single_id()
    {
        $person = factory(Fixtures\Person::class)->create();

        $this->assertEquals(Ids::getId($person->ID), $person->ID);
    }
}
