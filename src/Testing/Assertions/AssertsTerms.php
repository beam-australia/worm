<?php

namespace Beam\Worm\Testing\Assertions;

trait AssertsTerms
{

    /**
     * Assert two collections of terms are the same
     *
     * @param iterable $expected
     * @param iterable $actual
     * @return void
     */
    public function assertSameTerms(iterable $expected, iterable $actual): void
    {
        $expected = collect($expected);

        if (isset($expected->first()->term_id)) {
            $expected = $expected->pluck('term_id');
        }

        $actual = collect($actual);

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
