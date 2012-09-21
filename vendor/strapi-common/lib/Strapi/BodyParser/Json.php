<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi\BodyParser;

/**
 * Manages the json encoding or decoding of data for a response/request
 */
class Json extends InvokableParser
{
    /**
     * Encodes the given data as JSON-formatted string
     *
     * @param mixed $data
     * @return string
     */
    public function to($data)
    {
        return json_encode($data);
    }

    /**
     * Decodes the given JSON-formatted string
     *
     * JSON objects are decoded as associative arrays.
     *
     * @param string $string
     * @return mixed
     */
    public function from($string)
    {
        return json_decode($string, true);
    }
}