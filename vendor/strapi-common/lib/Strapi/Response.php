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
    protected $data = null;
    protected $status = 200;
    protected $headers = [];
    protected $bodyParser;


    /**
     * Constructs an HTTP response
     *
     * @param callable $bodyParser
     */
    public function __construct(callable $bodyParser = null)
    {
        if ($bodyParser === null) {
            $bodyParser = function($data){
                return serialize($data);
            };
        }
        $this->bodyParser = $bodyParser;
    }

    /**
     * Sets or retrieves the data of the response
     *
     * Response data must ultimately be converted to a string before it is
     * sent, but it is stored unserialized here for access and manipulation
     * prior to being sent.
     *
     * @param mixed $data
     * @return mixed
     */
    public function data($data = null)
    {
        if ($data !== null) {
            $this->data = $data;
        }
        return $this->data;
    }

    /**
     * Sets or retrieves a header value for this response
     *
     * @param string $name
     * @param string $value
     * @param bool   $replace
     * @return null|string
     */
    public function header($name, $value = null, $replace = true)
    {
        if ($value !== null) {
            $this->headers[$name] = [$value, $replace];
        }
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    /**
     * Sets or retrieves the http status for this response
     *
     * @param null|int $status
     * @return int
     */
    public function status($status = null)
    {
        if ($status !== null) {
            $this->status = $status;
        }
        return $this->status;
    }

    /**
     * Builds and returns raw response data
     *
     * The resulting array contains raw headers and the parsed body.
     *
     * @return array
     */
    public function build()
    {
        // we handle the body prior to headers so that the custom body parser
        // can manipulate the response before headers are compiled
        $body = '';
        if ($this->data !== null) {
            $body = call_user_func($this->bodyParser, $this->data, $this);
        }

        $headers = [];
        foreach ($this->headers as $name => $header) {
            $headers[] = [$name . ': ' . $header[0], $header[1]];
        }

        $status = $this->status();
        $prefix = strpos(PHP_SAPI, 'cgi') !== false ? 'Status:' : 'HTTP/1.1';
        $desc = isset(static::$statuses[$status]) ? static::$statuses[$status] : '';
        $headers[] = [$prefix . ' ' . $status . ' ' . $desc, true];

        return ['headers' => $headers, 'body' => $body];
    }
}