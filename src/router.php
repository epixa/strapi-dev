<?php
/* @var $this \Strapi\Runtime */

// Resources are loaded as classes with the namespace Api\Resource. They
// are assumed to be in a directory called "resources" inside the api root.
spl_autoload_register(function($className) {
    if (strpos($className, 'Api\\Resource\\') === 0) {
        $className = substr($className, 13);

        $ds = DIRECTORY_SEPARATOR;
        $path = $ds . str_replace('\\', $ds, $className);

        $path = str_replace('_', $ds, $path) . '.php';
        require $this->modulePath() . $ds . 'resources' . $path;
    }
});

// The default Strapi router requires pre-configured routes as well as a
// custom loader method for handling a route that matches the current
// request.
$routes = $this->load('config')['routes'];
return new Strapi\Router($routes, function(array $route) {
    $request = $this->load('request');
    $response = $this->load('response');

    $callback = [new $route['class'], strtolower($request->method())];
    $result = call_user_func($callback, $request, $response);
    if ($result !== null) {
        $response->data($result);
    }

    return $response;
});
