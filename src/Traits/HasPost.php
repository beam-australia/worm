<?php

namespace Beam\Worm\Traits;

use WP_Post;
use Beam\Worm\Types;

trait HasPost
{
    /**
     * Post instance
     *
     * @param WP_Post
     */
    public $post;

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
        } else if (isset($this->post->$key)) {
            return $this->post->$key;
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
        if (method_exists($this->post, $name)) {
            return $this->post->$name(...$arguments);
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

        if (property_exists($this->post, $key)) {
            wp_update_post([
                'ID' => $this->ID,
                $key => $value,
            ]);
            $this->post->$key = $value;
        } else {
            update_post_meta($this->ID, $key, $value);
            $this->$key = $value;
        }
    }
}
