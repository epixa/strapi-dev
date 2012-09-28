<?php

return [
    'render_exceptions' => false,
    'routes' => [
        'foo' => ['class' => 'Api\Resource\Foo'],
        'foo/:bar' => ['class' => 'Api\Resource\Foo', 'reqs' => ['bar' => '\d+']]
    ],
    'middleware' => [
        'middleware/exception-handler',
        'middleware/body-parser',
        'middleware/responder',
        'middleware/content-negotiation'
    ],
    'body_parsers' => [
        'application/json' => '\Strapi\BodyParser\Json',
        'application/x-www-form-urlencoded' => '\Strapi\BodyParser\Url'
    ]
];
