<?php

namespace Beam\Worm\Traits;

use WP_User;
use Beam\Worm\Types;

trait HasUser
{
    /**
     * User instance
     *
     * @param WP_User
     */
    public $user;

    /**
     * Magic property method
     *
     * @param string $key
     * @return void
     */
    public function __get(string $key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        } else if (isset($this->user->$key)) {
            return $this->user->$key;
        }

        return parent::__get($key);
    }

    /**
     * Magic method caller
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->user, $name)) {
            return $this->user->$name(...$arguments);
        }

        return parent::__call($name, $arguments);
    }

    /**
     * update_post_meta wrapper
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function update(string $key, $value): void
    {
        $value = Types::booleanToString($value);

        if (property_exists($this->user, $key)) {
            wp_update_user([
                'ID' => $this->ID,
                $key => $value,
            ]);
            $this->user->$key = $value;
        } else {
            update_user_meta($this->ID, $key, $value);
            $this->$key = $value;
        }

        $this->updateName($key);
    }

    /**
     * Updates name all fields
     *
     * @param string $key
     * @return void
     */
    private function updateName(string $key): void
    {
        if (false === in_array($key, ['first_name', 'last_name'])) {
            return;
        }

        $fullName = ucwords($this->first_name) . ' ' . ucwords($this->last_name);

        wp_update_user([
            'ID' => $this->ID,
            'user_nicename' => $fullName,
            'display_name' => $fullName,
            'first_name' => ucwords($this->first_name),
            'last_name' => ucwords($this->last_name),
        ]);
    }
}
