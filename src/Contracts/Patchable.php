<?php

namespace Beam\Worm\Contracts;

interface Patchable
{
    public function patch(array $attributes): void;
}
