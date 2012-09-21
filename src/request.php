<?php

$request = new Strapi\Request($_SERVER);
$request->params($_POST + $_GET);

if ($method = $request->env('REQUEST_METHOD')) {
    $request->method($method);
}

if ($uri = $request->env('REQUEST_URI')) {
    $request->uri($uri);
}

if ($type = $request->env('HTTP_CONTENT_TYPE')) {
    $request->type($type);
}

if (in_array($request->method(), ['POST', 'PUT', 'OPTIONS'])) {
    $request->body(@file_get_contents('php://input'));
}

//$request->accept($request->env('HTTP_ACCEPT') ?: 'application/json');
//$rawRequestData = @file_get_contents('php://input') ?: '';

//var_dump($request);die();

return $request;
