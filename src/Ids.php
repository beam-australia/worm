<?php

namespace Beam\Worm;

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
