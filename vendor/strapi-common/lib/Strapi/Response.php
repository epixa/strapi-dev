<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi;

/**
 * A standard HTTP response
 *
 * Manages the building of and sending of a response.
 */
class Response
{
    public static $statuses = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required'
    ];
    protected $body;
    protected $headers;
    protected $status;


    /**
     * Constructs a new http response
     *
     * @params int   $status
     * @params mixed $body
     * @params array $headers
     */
    public function __construct($status = 200, $body = null, array $headers = [])
    {
        $this->reset($status, $body, $headers);
    }

    /**
     * Sets or retrieves the body of the response
     *
     * Response body must ultimately be converted to a string before it is
     * sent, but it can be stored unserialized here for access and manipulation
     * prior to being sent.
     *
     * @param mixed $body
     * @return mixed
     */
    public function body($body = null)
    {
        if ($body !== null) {
            $this->body = $body;
        }
        return $this->body;
    }

    /**
     * Sets or retrieves a header for this response
     *
     * @param string $name
     * @param string $value
     * @param bool   $replace
     * @return null|string
     */
    public function header($name, $value = null, $replace = true)
    {
        $index = strtolower($name);
        if ($value !== null) {
            $prefix = $name . ':';
            if (!$replace && isset($this->headers[$index])) {
                $prefix = $this->headers[$index] . ',';
            }
            $this->headers[$index] = $prefix . ' ' . $value;
        }
        return isset($this->headers[$index]) ? $this->headers[$index] : null;
    }

    /**
     * Sets or retrieves the response's headers in bulk
     *
     * Setting headers via this method does not clear existing headers.
     *
     * @param array|null $headers
     * @return array
     */
    public function headers(array $headers = null)
    {
        if ($headers !== null) {
            foreach ($headers as $header => $value) {
                call_user_func([$this, 'header'], $header, $value);
            }
        }
        return $this->headers;
    }

    /**
     * Sets or retrieves the http status for this response
     *
     * Whenever the status is set/changed, the appropriate http status header
     * is also set/changed.
     *
     * @param null|int $status
     * @return int
     */
    public function status($status = null)
    {
        if ($status !== null) {
            $prefix = strpos(PHP_SAPI, 'cgi') !== false ? 'Status:' : 'HTTP/1.1';
            $desc = isset(static::$statuses[$status]) ? static::$statuses[$status] : '';
            $this->headers['status'] = $prefix . ' ' . $status . ' ' . $desc;
            $this->status = $status;
        }
        return $this->status;
    }

    /**
     * Resets the current response
     *
     * This can optionally set the status, body, and headers after reset.
     *
     * @param null|int $status
     * @param mixed    $body
     * @param array    $headers
     */
    public function reset($status = null, $body = null, array $headers = [])
    {
        $this->status = 200;
        $this->body = null;
        $this->headers = [];

        $this->status($status);
        $this->body($body);
        $this->headers($headers);
    }
}
