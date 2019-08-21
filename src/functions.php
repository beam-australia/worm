<?php

use Beam\Worm\Factories;

function factory(string $model, int $numberOf = 1)
{
    $builder = Factories\Builder::getInstance();

    return new Factories\Factory($builder, $model, $numberOf);
}

function request(string $route)
{
    return new Factories\Request($route);
}
