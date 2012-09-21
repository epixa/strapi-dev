<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi\BodyParser;

/**
 * Manages the parsing of data to and from a url query string
 */
class Url extends InvokableParser
{
    /**
     * Parses the provided array into a query string
     *
     * @param array $data
     * @return string
     * @throws \InvalidArgumentException If $data is not an array
     */
    public function to($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Expecting array, ' . gettype($data) . ' given');
        }
        return http_build_query($data);
    }

    /**
     * Parses the given query string into an array of parameters
     *
     * @param string $string
     * @return mixed
     */
    public function from($string)
    {
        parse_str($string, $params);
        return $params;
    }
}