<?php

return [
    'routes' => [
        'foo' => ['class' => 'Api\Resource\Foo'],
        'foo/:bar' => ['class' => 'Api\Resource\Foo', 'reqs' => ['bar' => '\d+']]
    ],
    'middleware' => [
        'middleware/body-parser',
        'middleware/responder',
        'middleware/content-negotiation' // must be after responder
    ],
    'body_parsers' => [
        'application/json' => '\Strapi\BodyParser\Json',
        'application/x-www-form-urlencoded' => '\Strapi\BodyParser\Url'
    ]
];