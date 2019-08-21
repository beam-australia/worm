<?php

namespace Tests\Factories;

use stdClass;
use Faker\Generator;
use Beam\Worm\Collection;
use Beam\Worm\Factories\Builder;
use Tests\Fixtures;

class BuilderTest extends \Tests\TestCase
{
    public function test_it_has_a_public_collection()
    {
        $builder = new Builder;

        $this->assertInstanceOf(Collection::class, $builder->factories);

        $this->assertEquals($builder->factories->count(), 0);
    }

    public function test_it_has_a_public_generator()
    {
        $builder = new Builder;

        $this->assertInstanceOf(Generator::class, $builder->faker);
    }

    public function test_it_can_define_model_factories()
    {
        $builder = new Builder;

        $callback = function () {
            return [
                'post_title' => 'FOO-BAR-TITLE',
                'post_content' => 'FOO-BAR-CONTENT',
            ];
        };

        $builder->define(Fixtures\Person::class, $callback);

        $callable = $builder->get(Fixtures\Person::class);

        $this->assertEquals($callable, $callback);

        $this->assertEquals($callable(), $callback());
    }

    public function test_it_calls_factory_with_faker()
    {
        $builder = new Builder;

        $callback = function (Generator $faker) {
            $this->assertInstanceOf(Generator::class, $faker);
            return ['foo-bar'];
        };

        $builder->define(Fixtures\Person::class, $callback);

        $result = $builder->call(Fixtures\Person::class);

        $this->assertEquals($result, ['foo-bar']);
    }

    public function test_it_can_check_for_a_factory()
    {
        $builder = new Builder;

        $builder->define(Fixtures\Person::class, function () {});

        $this->assertTrue($builder->has(Fixtures\Person::class));

        $this->assertFalse($builder->has(stdClass::class));
    }

    public function test_it_can_define_taxonomy_factories()
    {
        $builder = new Builder;

        $builder->defineTaxonomy(Fixtures\Breeds::class);

        $taxonomy = $builder->get(Fixtures\Breeds::class);

        $this->assertEquals($taxonomy::TAXONOMY, Fixtures\Breeds::TAXONOMY);
    }
}
