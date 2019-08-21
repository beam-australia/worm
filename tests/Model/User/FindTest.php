<?php

namespace Tests\Worm\Model\User;

use Beam\Worm\Enums\UserStatus;
use Beam\Worm\User;

class FindTest extends \Tests\TestCase
{
    public function test_it_can_find_all_users()
    {
        factory(User::class, 3)->create([
            'status' => UserStatus::PENDING,
        ]);

        factory(User::class, 3)->create([
            'status' => UserStatus::APPROVED,
        ]);

        $this->assertEquals(User::find()->count(), 6);
    }

    public function test_it_can_find_pending_users()
    {
        factory(User::class, 3)->create([
            'status' => UserStatus::PENDING,
        ]);

        factory(User::class, 3)->create([
            'status' => UserStatus::APPROVED,
        ]);

        User::pending()->each(function ($user) {
            $this->assertEquals($user->status, UserStatus::PENDING);
        });

        $this->assertEquals(User::pending()->count(), 3);
    }

    public function test_it_can_find_approved_users()
    {
        factory(User::class, 3)->create([
            'status' => UserStatus::PENDING,
        ]);

        factory(User::class, 3)->create([
            'status' => UserStatus::APPROVED,
        ]);

        User::approved()->each(function ($user) {
            $this->assertEquals($user->status, UserStatus::APPROVED);
        });

        $this->assertEquals(User::approved()->count(), 3);
    }
}
