<?php

namespace Tests\Factories;

use WP_Post;
use WP_Term;
use Beam\Worm\Collection;
use Illuminate\Support\Arr;
use Tests\Fixtures;

class FactoryTest extends \Tests\TestCase
{
    public function test_it_can_create_a_single_model()
    {
        $pet = factory(Fixtures\Pet::class)->create();

        $this->assertInstanceOf(Fixtures\Pet::class, $pet);

        $this->assertInstanceOf(WP_Post::class, get_post($pet->ID));
    }

    public function test_it_can_create_a_model_collection()
    {
        $collection = factory(Fixtures\Pet::class, 3)->create();

        $this->assertInstanceOf(Collection::class, $collection);

        $this->assertEquals($collection->count(), 3);

        foreach ($collection as $instance) {
            $this->assertInstanceOf(Fixtures\Pet::class, $instance);
        }
    }

    public function test_it_can_create_a_single_term()
    {
        $term = factory(Fixtures\Breeds::class)->create();

        $this->assertInstanceOf(WP_Term::class, $term);

        $this->assertEquals(Fixtures\Breeds::TAXONOMY, $term->taxonomy);
    }

    public function test_it_can_create_a_term_collection()
    {
        $collection = factory(Fixtures\Breeds::class, 3)->create();

        $this->assertInstanceOf(Collection::class, $collection);

        $this->assertEquals($collection->count(), 3);

        foreach ($collection as $term) {
            $this->assertInstanceOf(WP_Term::class, $term);
        }
    }

    public function test_it_can_merge_attributes()
    {
        $instance = factory(Fixtures\Pet::class)->create([
            'post_title' => 'Foo Bar Pet',
            'post_content' => 'Bar bar foo foo have you any wool',
        ]);

        $this->assertEquals($instance->post_title, 'Foo Bar Pet');

        $this->assertEquals($instance->post_content, 'Bar bar foo foo have you any wool');
    }

    public function test_it_can_get_attributes_for_a_model()
    {
        $attributes = factory(Fixtures\Pet::class)->attributes([
            'post_title' => 'Foo Bar Pet',
            'post_content' => 'Bar bar foo foo have you any wool',
        ]);

        $this->assertEquals($attributes['post_title'], 'Foo Bar Pet');
        $this->assertEquals($attributes['post_content'], 'Bar bar foo foo have you any wool');

        $this->assertTrue(Arr::has($attributes, [
            "name",
        ]));
    }
}
