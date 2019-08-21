<?php

namespace Beam\Worm;

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
}
