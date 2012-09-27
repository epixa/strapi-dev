<?php

return [
    'routes' => [
        'foo' => ['class' => 'Api\Resource\Foo'],
        'foo/:bar' => ['class' => 'Api\Resource\Foo', 'reqs' => ['bar' => '\d+']]
    ],
    'middleware' => [
        'middleware/body-parser'
    ],
    'body_parsers' => [
        'application/json' => '\Strapi\BodyParser\Json',
        'application/x-www-form-urlencoded' => '\Strapi\BodyParser\Url'
    ]
];