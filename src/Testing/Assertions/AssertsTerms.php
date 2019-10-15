<?php

namespace Beam\Worm\Testing\Assertions;

trait AssertsTerms
{

    /**
     * Assert two collections of terms are the same
     *
     * @param iterable $expected
     * @param mixed $actual
     * @return void
     */
    public function assertSameTerms(iterable $expected, $actual): void
    {
        $expected = collect($expected);

        $actual = is_iterable($actual) ? collect($actual) : collect([$actual]);

        if (isset($expected->first()->term_id)) {
            $expected = $expected->pluck('term_id');
        }

        if (isset($actual->first()->term_id)) {
            $actual = $actual->pluck('term_id');
        }

        $this->assertTrue(
            $expected->diff($actual)->isEmpty(),
            "Terms collections are not matching"
        );

        $this->assertEquals(
            $expected->count(),
            $actual->count(),
            "Post collections are not matching"
        );
    }
}
