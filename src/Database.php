<?php

namespace Beam\Worm;

class Database
{
    static function query(string $query)
    {
        global $wpdb;

        $wpdb->query($query);
    }

    static function getRows(string $query, array $arguments = [])
    {
        global $wpdb;

        if (count($arguments) > 2) {
            $query = $wpdb->prepare($query, $arguments);
        }

        return $wpdb->get_results($query, ARRAY_A);
    }

    static function getRow(string $query)
    {
        global $wpdb;

        return $wpdb->get_row($query, ARRAY_A);
    }

    static function getVar(string $query)
    {
        global $wpdb;

        return $wpdb->get_var($query);
    }
}
