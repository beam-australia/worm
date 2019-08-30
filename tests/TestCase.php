<?php

namespace Tests;

use Mockery;
use Beam\Worm\Testing\Assertions\AssertsPosts;
use Beam\Worm\Testing\Assertions\AssertsTerms;

abstract class TestCase extends \WP_UnitTestCase
{
    use AssertsPosts, AssertsTerms;

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }
}
