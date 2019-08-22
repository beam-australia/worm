<?php

namespace Beam\Worm\Contracts;

interface Relation
{
    public function sync($values): void;

    public function save($values): void;

    public function get();

    public function getIds();
}
