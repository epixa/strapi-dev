<?php
/* @var $this Strapi\Runtime */

$modules = $this->load('config')['middleware'];
if (!$modules) {
    return [];
}

$current = new Strapi\Middleware($this->load(reset($modules)));
$middleware = [$current];
do {
    $next = next($modules);
    if ($next !== false) {
        $next = new Strapi\Middleware($this->load($next));
        $current->next($next);
        $middleware[] = $next;
    }
    $current = $next;
} while ($current !== false);
return $middleware;