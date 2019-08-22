<?php

namespace Beam\Worm\Relations;

use Beam\Worm\Collection;
use Beam\Worm\Model;
use Beam\Worm\Contracts\Relation;

class HasTerms implements Relation
{
    /**
     * Calling model instance
     *
     * @var Model
     */
    public $instance;

    /**
     * Related taxonomy
     *
     * @var string
     */
    public $taxonomy;

    /**
     * Object constructor.
     *
     * @param Model $instance
     * @param string $taxonomy
     */
    public function __construct(Model $instance, string $taxonomy)
    {
        $this->instance = $instance;

        $this->taxonomy = $taxonomy;
    }


    /**
     * Get related term ids
     *
     * @return null|Model|Collection
     */
    public function getIds()
    {
        $terms = wp_get_object_terms($this->instance->ID, $this->taxonomy);

        if (is_wp_error($terms)) {
            return new Collection();
        }

        return (new Collection($terms))->pluck('term_id');
    }

    /**
     * Get related terms
     *
     * @return null|Model|Collection
     */
    public function get()
    {
        $terms = wp_get_object_terms($this->instance->ID, $this->taxonomy);

        if (is_wp_error($terms)) {
            return new Collection();
        }

        return new Collection($terms);
    }

    /**
     * Save related models
     *
     * @param iterable|int $values
     * @return void
     */
    public function save($values): void
    {
        $slugs = [];

        if (false === is_iterable($values)) {
            $values = [$values];
        }

        if ($values instanceof Collection) {
            if ($values->has('term_id')) {
                $values = $values->pluck('term_id')->toArray();
            } else {
                $values = $values->toArray();
            }
        }

        foreach ($values as $id) {
            if (is_object($id)) { // stdClass || WP_Term
                $slugs[] = $id->slug;
            } else if ($term = get_term((int) $id, $this->taxonomy)) {
                $slugs[] = $term->slug;
            }
        }

        if (count($slugs)) { // Empty will reset terms
            wp_set_object_terms($this->instance->ID, $slugs, $this->taxonomy, true);
        }
    }

    /**
     * Syncs related models
     *
     * @param iterable|int $values
     * @return void
     */
    public function sync($values): void
    {
        wp_delete_object_term_relationships($this->instance->ID, $this->taxonomy);

        $this->save($values);
    }
}
