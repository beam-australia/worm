<?php

namespace Tests\Fixtures;

use Beam\Worm\Collection;
use Beam\Worm\Post;
use Beam\Worm\Enums\PostStatus;

class Family extends Post
{
    const TYPE = 'family';

    public $attributes = [
        'surname',
        'ethnicity',
        'member_count',
        'eye_color',
        'mother_id',
        'father_id',
        'post_type',
    ];

    public $fillable = [
        'surname',
        'ethnicity',
        'member_count',
    ];

    public static $defaults = [
        'post_type' => Family::TYPE,
        'post_status' => PostStatus::PUBLISHED,
    ];

    public function getRelations(): Collection
    {
        return $this->relations([
            'mother' => $this->hasOne(Person::class, 'mother_id'),
            'father' => $this->hasOne(Person::class, 'father_id'),
            'children' => $this->hasMany(Person::class, 'family_id', 'user.user_email'),
            'pets' => $this->hasMany(Pet::class, 'family_id'),
        ]);
    }

    public function getTaxonomies(): Collection
    {
        return $this->taxonomies([
            Countries::TAXONOMY
        ]);
    }
}
