<?php

namespace Beam\Worm;

use WP_Term;
use Beam\Worm\Collection;

class Term
{
    /**
     * Fetch terms query
     *
     * @param array $args
     * @return Collection
     */
    public static function find(array $args = []): Collection
    {
        $args = array_merge([
            'taxonomy' => static::TAXONOMY,
            'hide_empty' => false,
            'include' => 'all',
        ], $args);

        $collection = new Collection;

        if ($terms = get_terms($args)) {

            foreach ($terms as $term) {

                if ($group = get_term_meta($term->term_id, 'group', true)) {
                    $term->group = $group;
                }

                $term->children = static::getChildren($term);

                $collection->push($term);
            }
        }

        return $collection;
    }

    /**
     * Returns a tree of the taxonomy
     *
     * @param integer $parent
     * @return Collection
     */
    public static function tree(int $parent = 0): Collection
    {
        $terms = new Collection(get_terms(static::TAXONOMY, [
            'parent' => $parent,
            'hide_empty' => false
        ]));

        $children = new Collection;

        foreach ($terms as $term) {

            if ($group = get_term_meta($term->term_id, 'group', true)) {
                $term->group = $group;
            }

            $term->children = static::getChildren($term);

            $children->push($term);
        }

        return $children;
    }

    /**
     * Return children with faked "All" term
     *
     * @param WP_Term $parent
     * @return Collection
     */
    private static function getChildren(WP_Term $parent): Collection
    {
        $children = get_terms(static::TAXONOMY, [
            'parent' => $parent->term_id,
            'hide_empty' => false,
        ]);

        if (is_wp_error($children)) {
            return [];
        }

        $children = is_array($children) ? new Collection($children) : new Collection;

        $fake = (object) [
            'term_id' => 0,
            'parent' => $parent->term_id,
            'slug' => 'all-'.$parent->slug,
            'name' => 'All '.$parent->name,
        ];

        $children->prepend($fake);

        return $children;
    }
}
