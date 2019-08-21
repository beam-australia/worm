<?php

namespace Tests\Fixtures;

use Tests\Fixtures\Taxonomies;
use Beam\Worm\Enums\PostStatus;
use Beam\Worm\Collection;
use Beam\Worm\Post;

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
            Taxonomies\Breeds::TAXONOMY,
            Taxonomies\Species::TAXONOMY,
            Taxonomies\Environments::TAXONOMY
        ]);
    }
}
