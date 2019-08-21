<?php

namespace Beam\Worm\Factories;

use Faker\Factory;
use Faker\Generator;
use Beam\Worm\Collection;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Builder
{
    /**
     * Builder singleton
     *
     * @var Builder
     */
    private static $instance;

    /**
     * Defined factories
     *
     * @var Collection
     */
    public $factories;

    /**
     * Faker generator instance
     *
     * @var Generator
     */
    public $faker;

    /**
     * Object constructor.
     */
    public function __construct()
    {
        $this->factories = new Collection;

        $this->faker = Factory::create();
    }

    /**
     * Late static binding a singleton
     *
     * @return Builder
     */
    public static function getInstance(): Builder
    {
        if (static::$instance === null) {
           static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Creates a builder instance, defining factories from a path
     *
     * @return Builder
     */
    public static function build(string $path): Builder
    {
        // We will name the Builder instance "factory" for syntax sugar
        $factory = static::getInstance();

        $iterator = new RecursiveDirectoryIterator($path);

        $dirIterator = new RecursiveIteratorIterator($iterator);

        foreach ($dirIterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                include_once $file->getPathname();
            }
        }

        return $factory;
    }

    /**
     * Define a new factory
     *
     * @param string $model
     * @param callable $callback
     * @return void
     */
    public function define(string $model, callable $callback)
    {
        $this->factories->put($model, $callback);
    }

    /**
     * Define a new factory
     *
     * @param string $model
     * @return void
     */
    public function defineTaxonomy(string $model)
    {
        $this->factories->put($model, $model);
    }

    /**
     * Call a factory callable
     *
     * @param string $model
     * @return array
     */
    public function call(string $model):? array
    {
        $factory = $this->factories->get($model);

        $this->faker->seed(rand());

        if (is_callable($factory)) {
            return call_user_func_array($factory, [$this->faker]);
        }

        return null;
    }

    /**
     * Check if a factory exists
     *
     * @param string $model
     * @return boolean
     */
    public function has(string $model): bool
    {
        return $this->factories->has($model);
    }

    /**
     * Get factory
     *
     * @param string $model
     * @return callable|string
     */
    public function get(string $model)
    {
        return $this->factories->get($model);
    }
}
