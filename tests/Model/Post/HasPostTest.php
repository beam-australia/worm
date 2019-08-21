<?php

namespace Tests\Worm\Model\Post;

use Beam\Worm\Post;

class HasPostTest extends \Tests\TestCase
{
    public function test_it_can_get_post_properties()
    {
        $post = factory(Post::class)->create();

        $this->assertEquals($post->post_title, $post->post->post_title);

        $this->assertEquals($post->post_content, $post->post->post_content);
    }

    public function test_it_can_get_own_properties()
    {
        $post = factory(Post::class)->create();

        $this->assertTrue(isset($post->attributes));

        $this->assertTrue(is_array($post->attributes));

        update_post_meta($post->ID, 'foo', 'bar');

        $this->assertEquals($post->foo, 'bar');
    }

    public function test_it_can_call_post_methods()
    {
        $post = factory(Post::class)->create();

        $array = $post->to_array();

        $this->assertArraySubset([
            'post_title' => $post->post_title,
            'post_content' => $post->post_content,
        ], $array);
    }

    public function test_it_can_update_post_values()
    {
        $post = factory(Post::class)->create();

        $post->update('post_title', 'The Post Title.');

        $this->assertEquals($post->post_title, 'The Post Title.');

        $post = $post->load();

        $this->assertEquals($post->post_title, 'The Post Title.');
    }

    public function test_it_can_update_post_meta_values()
    {
        $post = factory(Post::class)->create();

        $post->update('foo', 'foo meta value');

        $this->assertEquals($post->foo, 'foo meta value');
    }
}
