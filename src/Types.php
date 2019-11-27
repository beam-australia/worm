<?php

namespace Beam\Worm;

use Carbon\Carbon;

class Types
{
    /**
     * Casts bools to string values
     *
     * @param mixed $value
     * @return void
     */
    public static function booleanToString($value)
    {
        if ($value === false || $value === 0 || $value === '0') {
            return 'no';
        }

        if ($value === true || $value === 1 || $value === '1') {
            return 'yes';
        }

        return $value;
    }

    /**
     * Casts values
     *
     * @param mixed $values
     * @param string $cast
     * @return mixed
     */
    public static function cast($value, string $cast)
    {
        switch ($cast) {
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                if (is_string($value) && $value === 'no') {
                    return false;
                }
                if (is_string($value) && $value === 'yes') {
                    return true;
                }
                return (bool) $value;
            case 'int':
            case 'integer':
                return (int) $value;
            case 'date':
                return Carbon::parse($value)->toIso8601String();
        }
    }
}
