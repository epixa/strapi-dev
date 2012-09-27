<?php

$request = new Strapi\Request($_SERVER);
$request->params($_POST + $_GET);

if ($method = $request->env('REQUEST_METHOD')) {
    $request->method($method);
}

if ($uri = $request->env('REQUEST_URI')) {
    if (($pos = strpos($uri, '?')) !== false) {
        $uri = substr($uri, 0, $pos);
    }
    $request->uri($uri);
}

if ($type = $request->env('HTTP_CONTENT_TYPE')) {
    $request->type($type);
}

if ($accept = $request->env('HTTP_ACCEPT')) {
    $request->accept($accept);
}

if (in_array($request->method(), ['POST', 'PUT', 'OPTIONS'])) {
    $request->body(@file_get_contents('php://input'));
}

return $request;
