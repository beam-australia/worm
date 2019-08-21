<?php

namespace Beam\Worm\Factories;

use WP_REST_Request;

class Request
{
    /**
     * Request route
     *
     * @var string
     */
    public $route;

    /**
     * Object constructor.
     *
     * @param string $route
     */
    public function __construct(string $route)
    {
        $this->route = $route;
    }

    /**
     * GET Request
     *
     * @param array $params
     * @return WP_REST_Request
     */
    public function get(array $params = []): WP_REST_Request
    {
        if (count($params) > 0) {

            $params = http_build_query($params);

            $this->route .= "?$params";
        }

        $request = new WP_REST_Request('GET', $this->route);

        return $request;
    }

    /**
     * POST request
     *
     * @param array $params
     * @return WP_REST_Request
     */
    public function post(array $params = []): WP_REST_Request
    {
        $request = new WP_REST_Request('POST', $this->route);

        return $this->getJsonBody($request, $params);
    }

    /**
     * PATCH request
     *
     * @param array $params
     * @return WP_REST_Request
     */
    public function patch(array $params = []): WP_REST_Request
    {
        $request = new WP_REST_Request('PATCH', $this->route);

        return $this->getJsonBody($request, $params);
    }

    /**
     * PUT request
     *
     * @param array $params
     * @return WP_REST_Request
     */
    public function put(array $params = []): WP_REST_Request
    {
        $request = new WP_REST_Request('PUT', $this->route);

        return $this->getJsonBody($request, $params);
    }

    /**
     * DELETE request
     *
     * @param array $params
     * @return WP_REST_Request
     */
    public function delete(array $params = []): WP_REST_Request
    {
        $request = new WP_REST_Request('DELETE', $this->route);

        return $this->getJsonBody($request, $params);
    }

    /**
     * Builds a JSON request
     *
     * @param WP_REST_Request $request
     * @param array $params
     * @return void
     */
    private function getJsonBody(WP_REST_Request $request, array $params): WP_REST_Request
    {
        $request->set_header('content-type', 'application/json');

        if (count($params) > 0) {
            $request->set_body(json_encode($params));
        }

        return $request;
    }
}
