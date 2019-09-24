<?php

namespace Tests\Worm\Model;

use Beam\Worm\Types;

class BooleanToStringTest extends \Tests\TestCase
{
    public function test_it_casts_truthies()
    {
        $this->assertEquals(Types::booleanToString(1), 'yes');

        $this->assertEquals(Types::booleanToString('1'), 'yes');

        $this->assertEquals(Types::booleanToString(true), 'yes');
    }

    public function test_it_casts_falsies()
    {
        $this->assertEquals(Types::booleanToString(0), 'no');

        $this->assertEquals(Types::booleanToString('0'), 'no');

        $this->assertEquals(Types::booleanToString(false), 'no');
    }
}
