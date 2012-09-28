<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi;

/**
 * An exception that can be handled as an HTTP response
 *
 * The message must be appropriate for rendering in an HTTP response body. The
 * code should be a valid HTTP status code. The previous exception chain can be
 * set for use in debugging and/or logging, but in no way should it be included
 * in the HTTP response.
 */
class HttpErrorException extends \Exception
{
    /**
     * Constructs a new HTTP error exception
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->code = $code ?: 400;
    }
}
