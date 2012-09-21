<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi\BodyParser;

/**
 * Allows a body parser to be invoked as a function
 */
abstract class InvokableParser
{
    /**
     * Invoke behavior for this body parser
     *
     * If $data is a string, then we assume it needs to be decoded. Otherwise,
     * we assume it must be encoded.
     *
     * @param mixed $data
     * @return mixed
     */
    public function __invoke($data)
    {
        if (is_string($data)) {
            return $this->from($data);
        }
        return $this->to($data);
    }

    abstract public function from($string);
    abstract public function to($data);
}