<?php

namespace Beam\Worm;

use Beam\Worm\Relations\HasOne;
use Tightenco\Collect\Support\Collection as BaseCollection;
use Tightenco\Collect\Contracts\Support\Arrayable;

class Collection extends BaseCollection
{
    public function toArray(int $depth = 0)
    {
        return array_map(function ($value) use ($depth) {
            return $value instanceof Arrayable ? $value->toArray($depth) : $value;
        }, $this->items);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        if (isset($this->items[0]) && is_object($this->items[0])) {
            return property_exists($this->items[0], $key);
        }

        return array_key_exists($key, $this->items);
    }
}
