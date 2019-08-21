<?php

namespace Beam\Worm\Factories;

use WP_Term;
use Exception;
use Beam\Worm\Collection;
use Beam\Worm\Term;

class Factory
{
    /**
     * Model class
     *
     * @var string
     */
    public $model;

    /**
     * Number of instances to create
     *
     * @param string $model
     * @param integer $numberOf
     */
    public $numberOf;

    /**
     * Factory builder
     *
     * @var Collection
     */
    public $builder;

    /**
     * Object constructor.
     *
     * @param Builder $builder
     * @param string $model
     * @param integer $numberOf
     */
    public function __construct(Builder $builder, string $model, int $numberOf = 1)
    {
        $this->builder = $builder;

        $this->model = $model;

        $this->numberOf = $numberOf;
    }

    /**
     * Create an instance or collection
     *
     * @param array $attributes
     * @return void
     */
    public function create(array $attributes = [])
    {
        $items = new Collection;

        for ($i = 0; $i < $this->numberOf; $i++) {

            if (false === is_subclass_of($this->model, Term::class)) {

                $mergedAttrs = $this->attributes($attributes);

                $instance = $this->model::create($mergedAttrs);
            } else {
                $instance = $this->createTerm($this->model::TAXONOMY);
            }

            $items->push($instance);
        }

        return $items->count() > 1 ? $items : $items->first();
    }

    /**
     * Get attributes for an instance
     *
     * @param array $attributes
     * @return array
     */
    public function attributes(array $attributes = []): array
    {
        if (is_subclass_of($this->model, Term::class)) {
            throw new Exception("Cannot generate attributes for taxonomies");
        }

        if (false === $this->builder->has($this->model)) {
            throw new Exception("No factory defined for " . $this->model);
        }

        $factoryAttrs = $this->builder->call($this->model);

        return array_merge($factoryAttrs, $attributes);
    }

    /**
     * Generate a taxonomy term
     *
     * @param string $taxonomy
     * @return void
     */
    private function createTerm(string $taxonomy): WP_Term
    {
        $this->builder->faker->seed(rand());

        $term = wp_insert_term($this->builder->faker->word, $taxonomy);

        if (false === is_wp_error($term)) {
            return get_term($term['term_id'], $taxonomy);
        }

        return static::createTerm($taxonomy);
    }
}
