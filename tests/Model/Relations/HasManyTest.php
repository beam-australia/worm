<?php

namespace Tests\Worm\Model\Relations;

use Beam\Worm\Collection;
use Tests\Fixtures;

class HasManyTest extends \Tests\TestCase
{
    public function test_it_can_save_relations()
    {
        $family = factory(Fixtures\Family::class)->create();

        $children = factory(Fixtures\Person::class, 5)->create();

        $this->assertEmpty($family->children()->get());

        $family->children()->save($children);

        $this->assertSamePosts($family->children()->get(), $children);
    }

    public function test_it_can_save_multiple_related_items()
    {
        $family = factory(Fixtures\Family::class)->create();

        $this->assertEmpty($family->children()->get());

        for ($i = 1; $i < 15; $i++) {

            $child = factory(Fixtures\Person::class)->create();

            $family->children()->save($child);

            $this->assertEquals($family->children()->get()->count(), $i);
        }
    }

    public function test_it_can_access_relations_as_a_property()
    {
        $family = factory(Fixtures\Family::class)->create();

        $children = factory(Fixtures\Person::class, 5)->create();

        $this->assertTrue($family->children->isEmpty());

        $family->children()->save($children);

        $this->assertSamePosts($family->children, $children);

        $this->assertEquals($family->children->count(), 5);
    }

    public function test_it_orders_users_by_date()
    {
        $family = factory(Fixtures\Family::class)->create();

        $child1 = factory(Fixtures\Person::class)->create([
            'user_registered' => '2019-01-01 09:00:00',
        ]);
        $child3 = factory(Fixtures\Person::class)->create([
            'user_registered' => '2019-04-01 09:00:00',
        ]);;
        $child2 = factory(Fixtures\Person::class)->create([
            'user_registered' => '2019-02-01 09:00:00',
        ]);;
        $child4 = factory(Fixtures\Person::class)->create([
            'user_registered' => '2019-05-01 09:00:00',
        ]);;

        $children = new Collection([
            $child1,
            $child3,
            $child2,
            $child4,
        ]);

        $this->assertTrue($family->children->isEmpty());

        $family->children()->save($children);

        $dates = $family->children
            ->pluck('user.user_registered')
            ->toArray();

        $this->assertEquals($dates, [
            "2019-05-01 09:00:00",
            "2019-04-01 09:00:00",
            "2019-02-01 09:00:00",
            "2019-01-01 09:00:00",
        ]);
    }

    public function test_it_orders_posts_by_date()
    {
        $family = factory(Fixtures\Family::class)->create();

        $pet1 = factory(Fixtures\Pet::class)->create([
            'post_date' => '2019-01-01 09:00:00',
        ]);
        $pet3 = factory(Fixtures\Pet::class)->create([
            'post_date' => '2019-04-01 09:00:00',
        ]);;
        $pet2 = factory(Fixtures\Pet::class)->create([
            'post_date' => '2019-02-01 09:00:00',
        ]);;
        $pet4 = factory(Fixtures\Pet::class)->create([
            'post_date' => '2019-05-01 09:00:00',
        ]);;

        $pets = new Collection([
            $pet1,
            $pet3,
            $pet2,
            $pet4,
        ]);

        $this->assertTrue($family->pets->isEmpty());

        $family->pets()->save($pets);

        $dates = $family->pets
            ->pluck('post.post_date')
            ->toArray();

        $this->assertEquals($dates, [
            "2019-05-01 09:00:00",
            "2019-04-01 09:00:00",
            "2019-02-01 09:00:00",
            "2019-01-01 09:00:00",
        ]);
    }
}
