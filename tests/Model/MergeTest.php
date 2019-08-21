<?php

namespace Tests\Worm\Model\Post;

use Tests\Fixtures\Family;

class MergeTest extends \Tests\TestCase
{
    public function test_it_can_merge_meta()
    {
        $family = factory(Family::class)->create();

        $family->merge('furbies', [122]);

        $this->assertEquals($family->furbies, [122]);

        $family->merge('furbies', [255]);

        $this->assertEquals($family->furbies, [122,255]);

        $family->merge('furbies', [1,2,3,4,5]);

        $this->assertEquals($family->furbies, [122,255,1,2,3,4,5]);
    }
}
