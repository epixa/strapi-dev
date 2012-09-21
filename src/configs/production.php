<?php

return [
    'routes' => [
        'foo' => ['class' => 'Api\Resource\Foo'],
        'foo/:bar' => ['class' => 'Api\Resource\Foo', 'reqs' => ['bar' => '\d+']]
    ],
    'middleware' => [
        'middleware/body-parser'
    ],
    'encoders' => [
        'application/x-www-form-urlencoded' => '\Strapi\BodyParser\Url',
        'application/json' => '\Strapi\BodyParser\Json'
    ]
];