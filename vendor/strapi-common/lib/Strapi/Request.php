<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi;

/**
 * An HTTP request
 *
 * A representation of an HTTP request.
 */
class Request
{
    protected $body = null;
    protected $env = [];
    protected $method = 'GET';
    protected $params = [];
    protected $type = 'application/x-www-form-urlencoded';
    protected $uri = '/';

    /**
     * Constructs the current request environment
     *
     * @param array $env
     */
    public function __construct(array $env)
    {
        foreach ($env as $key => $value) {
            $this->env($key, $value);
        }
    }

    /**
     * Sets or retrieves the request's message body
     *
     * @param string $body
     * @return null|string
     */
    public function body($body = null)
    {
        if ($body !== null) {
            $this->body = (string)$body;
        }
        return $this->body;
    }

    /**
     * Sets or retrieves a specific environment variable
     *
     * @param string $name
     * @param null|string $value
     * @return null|string
     */
    public function env($name, $value = null)
    {
        if ($value !== null) {
            $this->env[$name] = $value;
        }
        return isset($this->env[$name]) ? $this->env[$name] : null;
    }

    /**
     * Sets or retrieves the http method
     *
     * @param null|string $method
     * @return string
     */
    public function method($method = null)
    {
        if ($method !== null) {
            $this->method = (string)$method;
        }
        return $this->method;
    }

    /**
     * Sets or retrieves the request parameters
     *
     * @param array $params
     * @return array
     */
    public function params(array $params = null)
    {
        if ($params !== null) {
            $this->params = $params;
        }
        return $this->params;
    }

    /**
     * Sets or retrieves the request's media type
     *
     * Media type is generally defined by the request's Content-Type header.
     *
     * @param null $type
     * @return string
     */
    public function type($type = null)
    {
        if ($type !== null) {
            $this->type = (string)$type;
        }
        return $this->type;
    }

    /**
     * Sets or retrieves the request uri
     *
     * @param null|string $uri
     * @return string
     */
    public function uri($uri = null)
    {
        if ($uri !== null) {
            $this->uri = (string)$uri;
        }
        return $this->uri;
    }
}