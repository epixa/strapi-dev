<?php

namespace StrapiMock;

class MockRequest extends \Strapi\Request
{
    protected $uri = '/';

    public function idealType(array $types)
    {
        return 'application/json';
    }

    public function typeMatch($mediaRange, array $types)
    {
        return 'application/json';
    }

    public function accept($accept = null)
    {
        return ['*/*'];
    }

    public function body($body = null)
    {
        return null;
    }

    public function env($name, $value = null)
    {
        return null;
    }

    public function method($method = null)
    {
        return 'GET';
    }

    public function params(array $params = null)
    {
        return [];
    }

    public function type($type = null)
    {
        return 'application/x-www-form-urlencoded';
    }

    public function uri($uri = null)
    {
        if ($uri !== null) {
            $this->uri = $uri;
        }
        return $this->uri;
    }
}