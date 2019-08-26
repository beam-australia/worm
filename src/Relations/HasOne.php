<?php

namespace Beam\Worm\Relations;

use Beam\Worm\Collection;
use Beam\Worm\Model;
use Beam\Worm\Contracts\Relation;
use Illuminate\Support\Arr;

class HasOne implements Relation
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
        $column = $this->column;

        return $this->instance->$column;
    }

    /**
     * Get related model(s)
     *
     * @return null|Model|Collection
     */
    public function get()
    {
        $column = $this->column;

        $modelClass = $this->model;

        if ($relationId = $this->instance->$column) {
            if (is_iterable($relationId)) {

                $collection = new Colleciton;

                foreach ($relationId as $id) {

                    $relatedInstance = (new $modelClass($id));

                    $collection->push($relatedInstance);
                }

                if (false === empty($this->key)) {
                    $collection = $collection->keyBy($this->key);
                }

            } else {
                return (new $modelClass($relationId));
            }
        }

        return null;
    }

    /**
     * Save related model(s)
     *
     * @param iterable|int $values
     * @return void
     */
    public function save($values): void
    {
        if ($values instanceof Collection) {
            $this->instance->update($this->column, $values->pluck('ID')->toArray());
        } else if ($values instanceof Model) {
            $this->instance->update($this->column, $values->ID);
        } else if (is_array($values)) {
            if (Arr::has($values, 'ID')) {
                $this->instance->update($this->column, $values['ID']);
            }
        } else {
            $this->instance->update($this->column, $values);
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
        // Pesuedo sync as one-to-one is always a sync.
        $this->save($values);
    }
}
