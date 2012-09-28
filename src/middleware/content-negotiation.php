<?php

return function($next) {
    $next();

    /* @var $this Strapi\Runtime */
    /* @var $response Strapi\Response */
    $response = $this->load('response');

    // if we have no response body, then we shouldn't bother trying to parse it!
    $body = $response->body();
    if ($body === null) {
        $next();
        return;
    }

    $parsers = $this->load('config')['body_parsers'];

    // if we have a valid parser for this type, then we should use it
    $type = $this->load('request')->idealType(array_keys($parsers));
    if (isset($parsers[$type])) {
        $parser = $parsers[$type];
        if (strpos($parser, '\\') === 0) {
            $parser = new $parser();
        } else {
            $parser = $this->load($parser);
        }
        $response->header('Content-Type', $type . '; charset=utf-8'); // todo: determine charset dynamically
        $response->body(call_user_func($parser, $body));
    } else {
        // we are unable to parse the data in a way that adheres to the Accept
        // header, so we respond with an error
        $response->status(406);
        $response->header('Content-Type', 'text/plain');
        $response->body('Only the following content-types are supported: ' . implode(' ', array_keys($parsers)));
    }
};