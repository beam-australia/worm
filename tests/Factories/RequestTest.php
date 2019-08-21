<?php

namespace Tests\Factories;

use WP_REST_Request;

class RequestTest extends \Tests\TestCase
{
    public function test_GET()
    {
        $request = request("/route/to/foo")->get([
            'bar' => 123,
            'bam' => [
                'hello',
                'world',
            ],
        ]);

        $this->assertInstanceOf(WP_REST_Request::class, $request);

        $this->assertEquals($request->get_method(), 'GET');

        $this->assertContains("/route/to/foo", $request->get_route());
        $this->assertContains("bar=123", $request->get_route());
        $this->assertContains("bam", $request->get_route());
        $this->assertContains("hello", $request->get_route());
        $this->assertContains("world", $request->get_route());
    }

    public function test_POST()
    {
        $params = [
            'bar' => 123,
            'bam' => [
                'hello',
                'world',
            ],
        ];

        $request = request("/route/to/foo")->post($params);

        $this->assertInstanceOf(WP_REST_Request::class, $request);

        $this->assertEquals($request->get_method(), 'POST');

        $this->assertContains("/route/to/foo", $request->get_route());

        $this->assertEquals(json_encode($params), $request->get_body());

        $this->assertEquals($request->get_header('content-type'), 'application/json');
    }

    public function test_PATCH()
    {
        $params = [
            'bar' => 123,
            'bam' => [
                'hello',
                'world',
            ],
        ];

        $request = request("/route/to/foo")->patch($params);

        $this->assertInstanceOf(WP_REST_Request::class, $request);

        $this->assertEquals($request->get_method(), 'PATCH');

        $this->assertContains("/route/to/foo", $request->get_route());

        $this->assertEquals(json_encode($params), $request->get_body());

        $this->assertEquals($request->get_header('content-type'), 'application/json');
    }

    public function test_PUT()
    {
        $params = [
            'bar' => 123,
            'bam' => [
                'hello',
                'world',
            ],
        ];

        $request = request("/route/to/foo")->put($params);

        $this->assertInstanceOf(WP_REST_Request::class, $request);

        $this->assertEquals($request->get_method(), 'PUT');

        $this->assertContains("/route/to/foo", $request->get_route());

        $this->assertEquals(json_encode($params), $request->get_body());

        $this->assertEquals($request->get_header('content-type'), 'application/json');
    }

    public function test_DELETE()
    {
        $params = [
            'bar' => 123,
            'bam' => [
                'hello',
                'world',
            ],
        ];

        $request = request("/route/to/foo")->delete($params);

        $this->assertInstanceOf(WP_REST_Request::class, $request);

        $this->assertEquals($request->get_method(), 'DELETE');

        $this->assertContains("/route/to/foo", $request->get_route());

        $this->assertEquals(json_encode($params), $request->get_body());

        $this->assertEquals($request->get_header('content-type'), 'application/json');
    }
}
