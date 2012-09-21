<?php

namespace Api\Resource;

class Foo
{
    /**
     * Retrieves the current collection of Foo resources
     *
     * GET /foo
     * @return array
     */
    public function get()
    {
        return [
            ['id' => '1', 'name' => 'bar']
        ];
    }

    /**
     * Creates a new Foo resource
     *
     * POST /foo
     * @param \Strapi\Request $request
     * @param \Strapi\Response $response
     */
    public function post($request, $response)
    {
        $params = $request->params();

        // todo: insert new resource

        $response->status(201);
        $response->header('Content-Type', 'application/json');
        $response->header('X-Location', '/foo/1');
        //$response->header('Location', '/foo/1');
    }
}