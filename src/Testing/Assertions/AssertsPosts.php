<?php

namespace Beam\Worm\Testing\Assertions;

trait AssertsPosts
{
    /**
     * Assert two collections of posts are the same
     *
     * @param iterable $expected
     * @param iterable $actual
     * @return void
     */
    public function assertSamePosts(iterable $expected, iterable $actual): void
    {
        $expected = collect($expected);

        if (isset($expected->first()['ID'])) {
            $expected = $expected->pluck('ID');
        }

        $actual = collect($actual);

        if (isset($actual->first()['ID'])) {
            $actual = $actual->pluck('ID');
        }

        $this->assertTrue(
            $expected->diff($actual)->isEmpty(),
            "Post collections are not matching"
        );
    }

    /**
     * Assert two posts are the same
     *
     * @param mixed $expected
     * @param mixed $actual
     * @return void
     */
    public function assertSamePost($expected, $actual): void
    {
        if (is_array($expected) && isset($expected['ID'])) {
            $expectedId = $expected['ID'];
        } else if (is_object($expected) && isset($expected->ID)) {
            $expectedId = $expected->ID;
        } else {
            $expectedId = $expected;
        }

        if (is_array($actual) && isset($actual['ID'])) {
            $actualId = $actual['ID'];
        } else if (is_object($actual) && isset($actual->ID)) {
            $actualId = $actual->ID;
        } else {
            $actualId = $actual;
        }

        $actualPost = get_post($actualId);

        $expectedPost = get_post($expectedId);

        $this->assertEquals(
            $expectedId,
            $actualId,
            "Post ID not the same."
        );

        $this->assertEquals(
            $actualPost->post_type,
            $expectedPost->post_type,
            "Post Type not the same."
        );
    }
}
