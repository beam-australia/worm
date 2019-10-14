<?php

namespace Beam\Worm;

use WP_Error;
use Beam\Worm\Enums\PostStatus;
use Beam\Worm\Traits\HasPost;
use Beam\Worm\Collection;

class Post extends Model
{
    use HasPost;

    const TYPE = 'post';

    public $attributes = [
        'post_status',
    ];

    public $fillable = [
        'post_status',
    ];

    public static $defaults = [
        'post_type' => Post::TYPE,
        'post_status' => PostStatus::PENDING,
    ];

    /**
     * Object constructor.
     *
     * @param int|Post $post
     */
    public function __construct($post = null)
    {
        if ($post instanceof Post) {
            $this->ID = $post->ID;
            $this->post = $post->post;
        } else if (is_numeric($post)) {
            $this->ID = $post;
            $this->post = get_post($post);
        }
    }

    /**
     * Create a new model
     *
     * @param array $attributes
     * @return Post
     */
    public static function create(array $attributes = []): Post
    {
        $postId = wp_insert_post(array_merge(static::$defaults, $attributes), true);

        if (is_wp_error($postId)) {
            throw new \Exception($postId->get_error_message());
        }

        $post = new static($postId);

        $post->setDefaults();

        $post->fill($attributes);

        return $post->load();
    }

    /**
     * Fetch posts query
     *
     * @param array $args
     * @return Collection
     */
    public static function find(array $args = []): Collection
    {
        $args = array_merge([
            'post_type' => static::TYPE,
            'posts_per_page' => -1,
            'post_status' => 'any',
        ], $args);

        if ($posts = get_posts($args)) {
            return new Collection($posts);
        }

        return new Collection;
    }

    /**
     * Return pending posts
     *
     * @return Collection
     */
    public static function pending(array $args = []): Collection
    {
        return static::find(array_merge([
            'post_status' => PostStatus::PENDING,
        ], $args));
    }

    /**
     * Return published posts
     *
     * @return Collection
     */
    public static function published(array $args = []): Collection
    {
        return static::find(array_merge([
            'post_status' => PostStatus::PUBLISHED,
        ], $args));
    }

    /**
     * Return draft posts
     *
     * @return Collection
     */
    public static function draft(array $args = []): Collection
    {
        return static::find(array_merge([
            'post_status' => PostStatus::DRAFT,
        ], $args));
    }
}
