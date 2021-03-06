<?php

namespace Beam\Worm;

use WP_Term;
use Beam\Worm\Model;

class Ids
{
    /**
     * Return an array of Ids
     *
     * @param mixed $values
     * @return array
     */
    public static function getIds($values): array
    {
        if ($values instanceof Model) {
            return [$values->ID];
        }

        if (is_numeric($values)) {
            return [$values];
        }

        if (is_iterable($values)) {

            $collection = new Collection($values);

            if ($collection->has('ID') || isset($collection->first()['ID'])) {
                return $collection->pluck('ID')->toArray();
            }

            if (is_numeric($collection->first())) {
                return $collection->toArray();
            }
        }

        return []; // None resolved.
    }

    /**
     * Return an array of term slugs
     *
     * @param mixed $values
     * @param string $taxonomy
     * @return array
     */
    public static function getSlugs($values, string $taxonomy): array
    {
        if ($values instanceof WP_Term) {
            return [$values->slug];
        }

        if (is_numeric($values)) {
            if (term_exists((int) $values, $taxonomy)) {
                $term = get_term((int) $values, $taxonomy);
                return [$term->slug];
            }
        }

        $values = is_iterable($values) ? $values : [$values];

        $slugs = [];

        $collection = new Collection($values);

        if ($collection->has('term_id') || isset($collection->first()['term_id'])) {
            $ids = $collection->pluck('term_id');
        } else if (is_numeric($collection->first())) {
            $ids = $collection->toArray();
        } else {
            $ids = $collection->map(function ($slug) use ($taxonomy) {
                if ($term = get_term_by('slug', $slug, $taxonomy)) {
                    return $term->term_id;
                }
            })->toArray();
        }

        if (count($ids)) {
            foreach ($ids as $id) {
                if (term_exists((int) $id, $taxonomy)) {
                    $term = get_term((int) $id, $taxonomy);
                    $slugs[] = $term->slug;
                }
            }
        }

        return $slugs;
    }

    /**
     * Return an array of Ids
     *
     * @param mixed $values
     * @return int
     */
    public static function getId($values): ?int
    {
        if ($values instanceof Model) {
            return (int) $values->ID;
        }

        if (is_numeric($values)) {
            return (int) $values;
        }

        if (is_iterable($values)) {

            $collection = new Collection($values);

            if ($collection->has('ID')) {
                return (int) $collection->pluck('ID')->first();
            }

            if (isset($collection->first()['ID'])) {
                return (int) $collection->first()['ID'];
            }

            if (is_numeric($collection->first())) {
                return (int) $collection->first();
            }
        }
    }
}
