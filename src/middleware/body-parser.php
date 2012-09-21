<?php
/* @var $this \Strapi\Runtime */

return function($next) {
    $request = $this->load('request');

    $method = $request->method();
    // per the http spec, only POST, PUT, and OPTIONS should have a body
    if (!in_array($method, ['POST', 'PUT', 'OPTIONS'])) {
        $next();
        return;
    }

    $type = $request->type();
    // if we're doing a standard POST request, then PHP has already parsed the
    // body for us, so there's no need to use our custom parsers
    if ($method == 'POST' && $type === 'application/x-www-form-urlencoded') {
        $request->params($_POST + $request->params());
        $next();
        return;
    }

    $body = $request->body();
    if ($body !== null && isset($this->load('config')['encoders'][$type])) {
        $encoder = $this->load('config')['encoders'][$type];
        if (strpos($encoder, '\\') === 0) {
            $encoder = new $encoder();
        } else {
            $encoder = $this->load($encoder);
        }
        $params = $encoder($request->body());
        if (is_array($params)) {
            $request->params($params + $request->params());
        }
    }
    $next();
};