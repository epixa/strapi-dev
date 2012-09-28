<?php

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

return function(array $route){
    $request = $this->load('request');
    $response = $this->load('response');

    $callback = [new $route['class'], strtolower($request->method())];
    if (!method_exists($callback[0], $callback[1])) {
        throw new RuntimeException($route['class'] . ' does not support method ' . $request->method(), 405);
    }
    $result = call_user_func($callback, $request, $response);
    if ($result !== null) {
        $response->body($result);
    }
};