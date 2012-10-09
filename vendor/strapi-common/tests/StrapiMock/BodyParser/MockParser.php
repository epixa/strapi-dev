<?php

namespace StrapiMock\BodyParser;

class MockParser extends \Strapi\BodyParser\InvokableParser
{
    public function from($string)
    {
        return [__METHOD__, $string];
    }

    public function to($data)
    {
        return [__METHOD__, $data];
    }
}