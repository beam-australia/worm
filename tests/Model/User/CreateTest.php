<?php

namespace Tests\Worm\Model\User;

use Beam\Worm\Enums\UserStatus;
use Beam\Worm\User;

class UserMetaTest extends \Tests\TestCase
{
    public function test_can_init_from_user_object()
    {
        $mock = factory(User::class)->create();

        $user = new User($mock);

        $this->assertEquals($user->user, $mock->user);
    }

    public function test_can_init_from_user_ID()
    {
        $mock = factory(User::class)->create();

        $user = new User($mock->ID);

        $this->assertEquals($user->user, $mock->user);
    }

    public function test_it_creates_user_with_properties()
    {
        $user = User::create([
            'user_email' => 'issac@beamaustralia.com.au',
            'first_name' => 'Isaac',
            'last_name' => 'Newton',
        ]);

        $user = $user->load();

        $this->assertEquals($user->user->first_name, 'Isaac');

        $this->assertEquals($user->user->last_name, 'Newton');
    }

    public function test_it_creates_user_with_default_properties()
    {
        $user = User::create([
            'user_email' => 'issac@beamaustralia.com.au',
            'first_name' => 'Isaac',
            'last_name' => 'Newton',
        ]);

        $this->assertTrue(isset($user->user->caps[USER::ROLE]));

        $this->assertEquals($user->status, UserStatus::APPROVED);
    }

    public function test_throws_exception_on_create_error()
    {
        $this->expectException(\Exception::class);

        factory(User::class)->create([
            'user_email' => 'exists@beamaustralia.com.au',
            'first_name' => 'Isaac',
            'last_name' => 'Newton',
        ]);

        User::create([
            'user_email' => 'exists@beamaustralia.com.au',
            'first_name' => 'Isaac',
            'last_name' => 'Newton',
        ]);
    }
}
