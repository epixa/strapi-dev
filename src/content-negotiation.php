<?php

return function($data, Strapi\Response $response) {
    // if $data is already a string, then we assume this was handled elsewhere
    if (is_string($data)) {
        return $data;
    }

    /* @var $this Strapi\Runtime */
    /* @var $request Strapi\Request */
    $request = $this->load('request');
    // todo: set $type based on Accept request header


    $parsers = $this->load('config')['body_parsers'];
    $type = $request->idealType(array_keys($parsers));

    // if we have a valid parser for this type, then we should use it
    if (isset($parsers[$type])) {
        $parser = $parsers[$type];
        if (strpos($parser, '\\') === 0) {
            $parser = new $parser();
        } else {
            $parser = $this->load($parser);
        }
        $response->header('Content-Type', $type . '; charset=utf-8');
        return call_user_func($parser, $data);
    }

    // we are unable to parse the data in a way that adheres to the Accept
    // header, so we respond with an error
    $response->status(406);
    $response->header('Content-Type', 'text/plain');
    return 'Only the following content-types are supported: ' . implode(' ', array_keys($parsers));
};