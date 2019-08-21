<?php

namespace Tests\Model\Post;

use Beam\Worm\Enums\PostStatus;
use Beam\Worm\Post;

class CreateTest extends \Tests\TestCase
{
    public function test_can_init_from_post_object()
    {
        $mock = factory(Post::class)->create();

        $post = new Post($mock);

        $this->assertEquals($post->post, $mock->post);
    }

    public function test_can_init_from_post_ID()
    {
        $mock = factory(Post::class)->create();

        $post = new Post($mock->ID);

        $this->assertEquals($post->post, $mock->post);
    }

    public function test_it_creates_post_with_properties()
    {
        $post = Post::create([
            'post_title' => 'The Title',
            'post_content' => 'The Content',
            'post_excerpt' => 'The Excerpt',
        ]);

        $this->assertEquals($post->post_title, 'The Title');

        $this->assertEquals($post->post_content, 'The Content');

        $this->assertEquals($post->post_excerpt, 'The Excerpt');
    }

    public function test_it_creates_post_with_default_properties()
    {
        $post = Post::create([
            'post_title' => 'The Title',
            'post_content' => 'The Content',
            'post_excerpt' => 'The Excerpt',
        ]);

        $this->assertEquals($post->post_type, Post::TYPE);

        $this->assertEquals($post->post_status, PostStatus::PENDING);
    }
}
