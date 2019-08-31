<?php

namespace Beam\Worm\Relations;

use Beam\Worm\Ids;
use Beam\Worm\Collection;
use Beam\Worm\Model;
use Beam\Worm\Post;
use Beam\Worm\User;
use Beam\Worm\Database;
use Beam\Worm\Contracts\Relation;
use Illuminate\Support\Arr;

class HasMany implements Relation
{
    /**
     * Calling model instance
     *
     * @var Model
     */
    public $instance;

    /**
     * Related model class
     *
     * @var string
     */
    public $model;

    /**
     * ID column on related instances
     *
     * @var string
     */
    public $column;

    /**
     * Property to key collections with
     *
     * @var [type]
     */
    public $key = null;

    /**
     * Object constructor.
     *
     * @param Model $intance
     * @param string $model
     * @param string $column
     * @param string $key
     */
    public function __construct(Model $instance, string $model, string $column, string $key = null)
    {
        $this->instance = $instance;

        $this->model = $model;

        $this->column = $column;

        $this->key = $key;
    }


    /**
     * Get related model id(s)
     *
     * @return null|Model|Collection
     */
    public function getIds()
    {
        if (is_subclass_of($this->model, Post::class)) {
            $query = "
                SELECT wp_posts.ID
                FROM wp_posts
                LEFT JOIN
                    wp_postmeta ON wp_postmeta.post_id = wp_posts.ID
                    AND wp_postmeta.meta_key = '$this->column'
                WHERE
                    wp_postmeta.meta_value = ".$this->instance->ID."
                    AND wp_posts.post_type = '".$this->model::TYPE."'
                ORDER BY
                    DATE(wp_posts.post_date) DESC
            ";
        } else if (is_subclass_of($this->model, User::class)) {
            $query = "
                SELECT wp_users.ID
                FROM wp_users
                LEFT JOIN
                    wp_usermeta ON wp_usermeta.user_id = wp_users.ID AND wp_usermeta.meta_key = '".$this->column."'
                WHERE
                    wp_usermeta.meta_value = ".$this->instance->ID."
                ORDER BY
                    DATE(wp_users.user_registered) DESC
            ";
        }

        $rows = new Collection(Database::getRows($query));

        return $rows->pluck('ID')->toArray();
    }

    /**
     * Get related models
     *
     * @return null|Model|Collection
     */
    public function get()
    {
        $ids = $this->getIds();

        $collection = new Collection;

        foreach ($ids as $id) {
            $modelClass = $this->model;
            $collection->push(new $modelClass($id));
        }

        if (false === empty($this->key)) {
            $collection = $collection->keyBy($this->key);
        }

        return $collection;
    }

    /**
     * Save related models
     *
     * @param iterable|int $values
     * @return void
     */
    public function save($values): void
    {
        $ids = Ids::getIds($values);

        foreach ($ids as $id) {
            $modelClass = $this->model;
            $relatedInstance = new $modelClass($id);
            $relatedInstance->update($this->column, $this->instance->ID);
        }
    }

    /**
     * Syncs related models
     *
     * @param iterable|int $values
     * @return void
     */
    public function sync($values): void
    {
        if ($collection = $this->get()) {
            foreach ($collection as $item) {
                $item->update($this->column, null);
            }
        }

        $this->save($values);
    }
}
