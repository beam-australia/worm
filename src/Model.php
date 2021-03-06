<?php

namespace Beam\Worm;

use Beam\Worm\Collection;
use Beam\Worm\Types;
use Illuminate\Support\Str;
use Beam\Worm\Relations\HasMany;
use Beam\Worm\Relations\HasOne;
use Beam\Worm\Relations\HasTerms;
use Tightenco\Collect\Contracts\Support\Arrayable;

class Model implements Arrayable
{
    /**
     * Database id
     *
     * @var int
     */
    public $ID;

    /**
     * Attribute keys
     *
     * @var array
     */
    public $attributes = [];

    /**
     * Mass assignable keys
     *
     * @var array
     */
    public $fillable = [];

    /**
     * Default meta values
     *
     * @var array
     */
    public static $defaults = [];

    /**
     * Cast to array forms
     *
     * @var array
     */
    public $cast = [];

    /**
     * Magic property method
     *
     * @param string $key
     * @return void
     */
    public function __get(string $key)
    {
        if ($this->getRelations()->has($key)) {
            return $this->getRelations()->get($key)->get();
        }

        if ($this->getTaxonomies()->has($key)) {
            return $this->getTaxonomies()->get($key)->get();
        }
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
        $relation = Str::snake($name);

        if ($this->getRelations()->has($relation)) {
            return $this->getRelations()->get($relation);
        }

        if ($this->getTaxonomies()->has($relation)) {
            return $this->getTaxonomies()->get($relation);
        }

        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
    }

    /**
     * Defines a HasOne relationship
     *
     * @return HasOne
     */
    public function hasOne(string $model, string $column, string $key = null): HasOne
    {
        return new HasOne($this, $model, $column, $key);
    }

    /**
     * Defines a HasMany relationship
     *
     * @return HasMany
     */
    public function hasMany(string $model, string $column, string $key = null): HasMany
    {
        return new HasMany($this, $model, $column, $key);
    }

    /**
     * Defines a HasTerms relationship
     *
     * @return HasTerms
     */
    public function hasTerms(string $taxonomy): HasTerms
    {
        return new HasTerms($this, $taxonomy);
    }

    /**
     * Sets relations collection
     *
     * @param array $relations
     * @return Collection
     */
    public function relations(array $relations = []): Collection
    {
        return new Collection($relations);
    }

    /**
     * Sets taxonomies collection
     *
     * @param array $taxonomies
     * @return Collection
     */
    public function taxonomies(array $taxonomies = []): Collection
    {
        $collection = new Collection;

        foreach ($taxonomies as $taxonomy) {
            $collection->put($taxonomy, $this->hasTerms($taxonomy));
        }

        return $collection;
    }

    /**
     * Set default values
     *
     * @return void
     */
    public function setDefaults(): void
    {
        foreach (static::$defaults as $key => $default) {
            $this->update($key, $default);
        }
    }

    /**
     * Merges new value into existing values
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function merge(string $key, $value): void
    {
        if (!isset($this->$key)) {
            $original = [];
        } else {
            $original = $this->$key;
        }

        $this->update($key, array_merge($original, $value));
    }

    /**
     * Mass attribute assignment
     *
     * @param array $attributes
     * @return void
     */
    public function fill(array $attributes): void
    {
        foreach ($this->fillable as $key) {
            if (isset($attributes[$key])) {
                $this->update($key, $attributes[$key]);
            }
        }
    }

    /**
     * Mass save taxonomy terms
     *
     * @param array $values
     * @return void
     */
    public function saveTaxonomies(array $values): void
    {
        foreach ($values as $taxonomy => $terms) {
            if ($this->getTaxonomies()->has($taxonomy)) {
                $this->getTaxonomies()->get($taxonomy)->save($terms);
            }
        }
    }

    /**
     * Mass sync taxonomy terms
     *
     * @param iterable $values
     * @return void
     */
    public function syncTaxonomies(iterable $values): void
    {
        foreach ($values as $taxonomy => $terms) {
            if ($this->getTaxonomies()->has($taxonomy)) {
                $this->getTaxonomies()->get($taxonomy)->sync($terms);
            }
        }
    }

    /**
     * Mass save relations
     *
     * @param array $values
     * @return void
     */
    public function saveRelations(array $values): void
    {
        foreach ($values as $key => $value) {
            if (Str::endsWith($key, '_id')) {
                $values[Str::before($key, '_id')] = $value;
            }
        }

        foreach ($values as $relation => $models) {
            if ($this->getRelations()->has($relation)) {
                $this->getRelations()->get($relation)->save($models);
            }
        }
    }

    /**
     * Mass sync relations terms
     *
     * @param iterable $values
     * @return void
     */
    public function syncRelations(iterable $values): void
    {
        foreach ($values as $key => $value) {
            if (Str::endsWith($key, '_id')) {
                $values[Str::before($key, '_id')] = $value;
            }
        }

        foreach ($values as $relation => $models) {
            if ($this->getRelations()->has($relation)) {
                $this->getRelations()->get($relation)->sync($models);
            }
        }
    }

    /**
     * Load values on the model
     *
     * @return void
     */
    public function load()
    {
        return new static($this->ID);
    }

    /**
     * Array of model attributes
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $data = [
            'ID' => $this->ID,
        ];

        foreach ($this->attributes as $key) {
            if (isset($this->cast[$key])) {
                if (is_array($this->$key)) {
                    $values = [];
                    foreach ($this->$key as $val) {
                        $values[] = Types::cast($val, $this->cast[$key]);
                    }
                    $data[$key] = $values;
                } else {
                    $data[$key] = Types::cast($this->$key, $this->cast[$key]);
                }
            } else {
                $data[$key] = $this->$key;
            }
        }

        return $data;
    }

    /**
     * Array of model taxonomies
     *
     * @return array
     */
    public function taxonomiesToArray(): array
    {
        $data = [];

        if ($this->getTaxonomies()->isNotEmpty()) {
            foreach ($this->getTaxonomies() as $taxonomy => $relation) {
                if ($collection = $relation->get($this)) {
                    if ($collection->isNotEmpty()) {
                        $data[$taxonomy] = $collection->toArray();
                    } else {
                        $data[$taxonomy] = [];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Array of a model relation
     *
     * @param bool $isChild
     * @param string $relationName
     * @return array
     */
    public function relationToArray(string $relationName, bool $isChild = false): array
    {
        $data = [];

        if ($relation = $this->getRelations()->get($relationName)) {
            if ($relation instanceof HasMany) {
                if ($related = $relation->get($this)) {
                    $data = $related->toArray($isChild);
                } else {
                    $data = [];
                }
            }
            if ($relation instanceof HasOne) {
                if ($related = $relation->get($this)) {
                    $data = $related->toArray($isChild);
                }
            }
        }

        return $data;
    }

    /**
     * Array of model relations
     *
     * @param bool $isChild
     * @return array
     */
    public function relationsToArray(bool $isChild = false): array
    {
        $data = [];

        if ($this->getRelations()->isNotEmpty()) {
            foreach ($this->getRelations() as $relationName => $relation) {
                $data[$relationName] = $this->relationToArray($relationName, $isChild);
            }
        }

        return $data;
    }

    /**
     * Returns an arrayable representation of the object
     *
     * @param bool $isChild
     * @return array
     */
    public function toArray(bool $isChild = false): array
    {
        $instance = $this->load();

        $relations = $isChild === false ? $instance->relationsToArray(true) : [];

        return array_merge(
            $instance->attributesToArray(),
            $instance->taxonomiesToArray(),
            $relations
        );
    }
}
