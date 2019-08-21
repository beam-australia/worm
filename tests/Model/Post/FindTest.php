<?php

namespace Tests\Worm\Model\Post;

use Beam\Worm\Enums\PostStatus;
use Beam\Worm\Post;

class FindTest extends \Tests\TestCase
{
    public function test_it_can_find_all_posts()
    {
        factory(Post::class, 3)->create([
            'post_status' => PostStatus::PENDING,
        ]);

        factory(Post::class, 3)->create([
            'post_status' => PostStatus::PUBLISHED,
        ]);

        $this->assertEquals(Post::find()->count(), 6);
    }

    public function test_it_can_find_pending_posts()
    {
        factory(Post::class, 3)->create([
            'post_status' => PostStatus::PENDING,
        ]);

        factory(Post::class, 3)->create([
            'post_status' => PostStatus::PUBLISHED,
        ]);

        Post::pending()->each(function ($job) {
            $this->assertEquals($job->post_status, PostStatus::PENDING);
        });

        $this->assertEquals(Post::pending()->count(), 3);
    }

    public function test_it_can_find_published_posts()
    {
        factory(Post::class, 3)->create([
            'post_status' => PostStatus::PENDING,
        ]);

        factory(Post::class, 3)->create([
            'post_status' => PostStatus::PUBLISHED,
        ]);

        Post::published()->each(function ($job) {
            $this->assertEquals($job->post_status, PostStatus::PUBLISHED);
        });

        $this->assertEquals(Post::published()->count(), 3);
    }
}
