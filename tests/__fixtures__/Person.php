<?php

namespace Tests\Fixtures;

use Beam\Worm\Enums\UserStatus;
use Beam\Worm\Collection;
use Beam\Worm\User;

class Person extends User
{
    const ROLE = 'person';

    public $attributes = [
        'user_registered',
        'first_name',
        'family_id',
        'user_email',
    ];

    public $fillable = [
        'user_registered',
        'first_name',
        'user_email',
    ];

    public static $defaults = [
        'status' => UserStatus::APPROVED,
    ];

    public function getRelations(): Collection
    {
        return $this->relations([
            'family' => $this->hasOne(Family::class, 'family_id'),
        ]);
    }

    public function getTaxonomies(): Collection
    {
        return $this->taxonomies([]);
    }
}
