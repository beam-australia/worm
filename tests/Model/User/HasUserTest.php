<?php

namespace Tests\Worm\Model\User;

use Beam\Worm\User;

class HasUserTest extends \Tests\TestCase
{
    public function test_it_can_get_user_properties()
    {
        $user = factory(User::class)->create();

        $this->assertEquals($user->first_name, $user->user->first_name);

        $this->assertEquals($user->last_name, $user->user->last_name);
    }

    public function test_it_can_get_own_properties()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(isset($user->attributes));

        $this->assertTrue(is_array($user->attributes));

        update_user_meta($user->ID, 'foo', 'bar');

        $this->assertEquals($user->foo, 'bar');
    }

    public function test_it_can_call_user_methods()
    {
        $user = factory(User::class)->create();

        $this->assertTrue($user->has_cap('staff'));
    }

    public function test_it_can_update_user_values()
    {
        $user = factory(User::class)->create();

        $user->update('first_name', 'Bobba La Fette');

        $this->assertEquals($user->first_name, 'Bobba La Fette');

        $user = $user->load();

        $this->assertEquals($user->first_name, 'Bobba La Fette');
    }

    public function test_it_can_update_user_meta_values()
    {
        $user = factory(User::class)->create();

        $user->update('foo', 'foo meta value');

        $this->assertEquals($user->foo, 'foo meta value');

        $user = $user->load();

        $this->assertEquals($user->foo, 'foo meta value');
    }
}
