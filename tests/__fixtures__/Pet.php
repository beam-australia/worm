<?php

namespace Tests\Fixtures;

use Beam\Worm\Post;
use Beam\Worm\Collection;
use Beam\Worm\Enums\PostStatus;

class Pet extends Post
{
    const TYPE = 'pet';

    public $attributes = [
        'name',
        'post_type',
    ];

    public $fillable = [
        'name',
    ];

    public static $defaults = [
        'post_type' => Pet::TYPE,
        'post_status' => PostStatus::PUBLISHED,
    ];

    public function getRelations(): Collection
    {
        return $this->relations([
            'family' => $this->hasOne(Family::class, 'family_id'),
        ]);
    }

    public function getTaxonomies(): Collection
    {
        return $this->taxonomies([
            Breeds::TAXONOMY
        ]);
    }
}
