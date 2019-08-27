<?php

namespace Tests\Enums;

use Tests\Fixtures\Enums\Kingdoms;

class BuilderTest extends \Tests\TestCase
{
    public function test_it_can_return_a_random_value()
    {
        $first = Kingdoms::random();

        $this->assertEquals((new Kingdoms($first))->getValue(), $first);
    }
}
