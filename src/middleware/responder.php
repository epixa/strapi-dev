<?php

return function($next) {
    $next();

    /* @var $this Strapi\Runtime */
    /* @var $response Strapi\Response */
    $response = $this->load('response');

    foreach ($response->headers() as $header) {
        header($header);
    }
    echo $response->body();
};