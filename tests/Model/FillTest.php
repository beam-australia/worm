<?php

namespace Tests\Worm\Model\Post;

use Tests\Fixtures\Family;

class FillTest extends \Tests\TestCase
{
    public function test_it_can_fill_attributes()
    {
        $family = factory(Family::class)->create();

        $attributes = [
            'surname' => 'Drewskies',
            'ethnicity' => 'Asian',
            'member_count' => 12,
        ];

        $this->assertNotEquals($family->surname, $attributes['surname']);
        $this->assertNotEquals($family->ethnicity, $attributes['ethnicity']);
        $this->assertNotEquals($family->member_count, $attributes['member_count']);

        $family->fill($attributes);

        $this->assertEquals($family->surname, $attributes['surname']);
        $this->assertEquals($family->ethnicity, $attributes['ethnicity']);
        $this->assertEquals($family->member_count, $attributes['member_count']);
    }

    public function test_it_only_fills_fillable_attributes()
    {
        $family = factory(Family::class)->create();

        $attributes = [
            'surname' => 'Drewskies',
            'ethnicity' => 'Asian',
            'member_count' => 12,
            'eye_color' => 'red',
        ];

        $this->assertNotEquals($family->eye_color, $attributes['eye_color']);

        $family->fill($attributes);

        $this->assertNotEquals($family->eye_color, $attributes['eye_color']);
    }
}
