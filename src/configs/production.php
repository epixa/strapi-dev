<?php

return [
    'routes' => [
        'foo' => ['class' => 'Api\Resource\Foo'],
        'foo/:bar' => ['class' => 'Api\Resource\Foo', 'reqs' => ['bar' => '\d+']]
    ],
    'middleware' => [
        'middleware/test', 'middleware/test2'
    ]
];