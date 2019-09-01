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

        $slugs = [];

        if (is_iterable($values)) {

            $collection = new Collection($values);

            if ($collection->has('term_id') || isset($collection->first()['term_id'])) {
                $ids = $collection->pluck('term_id');
            } else if (is_numeric($collection->first())) {
                $ids = $collection->toArray();
            }

            if (count($ids)) {
                foreach ($ids as $id) {
                    $id = (int) $id;
                    if (term_exists($id, $taxonomy)) {
                        $term = get_term($id, $taxonomy);
                        $slugs[] = $term->slug;
                    }
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
    public static function getId($values):? int
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
