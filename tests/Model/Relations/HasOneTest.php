<?php

namespace Tests\Worm\Model\Relations;

use Tests\Fixtures;

class HasOneTest extends \Tests\TestCase
{
    public function test_it_can_save_a_relation()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $family = factory(Fixtures\Family::class)->create();

        $this->assertNull($pet->family);

        $pet->family()->save($family);

        $this->assertSamePost($pet->family()->get(), $family);
    }

    public function test_it_can_access_relation_as_property()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $family = factory(Fixtures\Family::class)->create();

        $this->assertNull($pet->family);

        $pet->family()->save($family);

        $this->assertSamePost($pet->family, $family);
    }
}
