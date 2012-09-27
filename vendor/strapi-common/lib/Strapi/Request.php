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
    protected $accept = ['*/*' => 1];
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
     * Returns the ideal media type based on acceptable media ranges
     *
     * This will search through the given set of parsable media types and
     * return the type that best matches the request's acceptable media ranges.
     *
     * A "best match" is one where the media type fits the definition of one of
     * the acceptable media ranges, and no types match another acceptable media
     * type with a higher priority.
     *
     * @param array $types
     * @return null|string
     */
    public function idealType(array $types)
    {
        $idealType = null;
        foreach ($this->accept as $mediaRange) {
            if ($idealType = $this->typeMatch($mediaRange, $types)) {
                break;
            }
        }
        return $idealType;
    }

    /**
     * Extracts a type from the given set that matches the given media range
     *
     * The first type match that is found is returned. If no match is found,
     * then this returns null.
     *
     * @param string $mediaRange
     * @param array  $types
     * @return null|string
     */
    public function typeMatch($mediaRange, array $types)
    {
        foreach ($types as $type) {
            // exact matches are best
            if ($type === $mediaRange) {
                return $type;
            }
            // if no exact match and no wildcard in range, then not this type
            if (($pos = strpos($mediaRange, '*')) === false) {
                continue;
            }
            // begins with a wildcard, anything goes
            if ($pos === 0) {
                return $type;
            }
            // subtype must be a wildcard, so ignore subtype
            if (strpos($type, substr($mediaRange, 0, $pos)) === 0) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Sets or retrieves the request's acceptable media ranges
     *
     * Acceptable media ranges are generally defined by the request's Accept
     * header.
     *
     * The string of media ranges is parsed in a similar way to the Accept
     * header definition in the HTTP 1.1 spec.
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.1
     *
     * After being parsed, the acceptable media ranges are stored in an array
     * in descending order according to priority (highest priorities first).
     *
     * @param null|string $accept
     * @return array
     */
    public function accept($accept = null)
    {
        if ($accept !== null) {
            $accept = str_replace(' ', '', $accept);
            $types = explode(',', $accept);
            $accept = [];
            foreach ($types as $type) {
                $mediaRange = $type;
                $quality = 1;
                if (($pos = strpos($type, ';')) !== false) {
                    $mediaRange = substr($type, 0, $pos);
                    if (preg_match('/q=(1[\.0]{0,4}|0[\.\d]{0,4})/', $type, $matches, 0, $pos)) {
                        $quality = (float)$matches[1];
                    }
                }
                if (!isset($accept[$mediaRange]) || $accept[$mediaRange] < $quality) {
                    $accept[$mediaRange] = $quality;
                }
            }
            arsort($accept);
            $this->accept = array_keys($accept);
        }
        return $this->accept;
    }

    /**
     * Sets or retrieves the request's message body
     *
     * @param null|string $body
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
     * The media type must contain a slash somewhere after the first character.
     *
     * @param null $type
     * @return string
     */
    public function type($type = null)
    {
        if ($type !== null && strpos($type, '/')) {
            $this->type = $type;
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