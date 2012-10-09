<?php

namespace StrapiMock;

class MockRouter extends \Strapi\Router
{
    public function __construct(array $routes)
    {}

    public function add($scheme, $class, array $requirements = [])
    {
        return ['class' => 'StrapiMock\MockResource'];
    }

    public function route($request)
    {
        if ($request->uri() == '/404') {
            return null;
        }
        return ['class' => 'StrapiMock\MockResource'];
    }

    protected function match(array $segments, array $route)
    {
        return true;
    }
}
