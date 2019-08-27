<?php

namespace Beam\Worm\Enums;

use MyCLabs\Enum\Enum as BaseEnum;

class Enum extends BaseEnum
{
    public static function random(): string
    {
        $collection = collect(static::toArray());

        return $collection->random();
    }
}
