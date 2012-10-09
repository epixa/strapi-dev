<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi;

/**
 * A basic HTTP router
 *
 * Manages the compiling and parsing of route definitions and routing requests
 * to a custom handler.
 */
class Router
{
    protected $routes = [];

    /**
     * Constructs a basic router
     *
     * The first argument contains an array of route definitions. The key of
     * each array element is parsed as its routing scheme. The value must be
     * an array with at least a "class" specified.  The route's array may also
     * contain an array of "reqs" (requirements).
     *
     * Config example:
     *  [
     *      'foo' => ['class' => 'Api\Resource\Foo'],
     *      'foo/:bar' => ['class' => 'Api\Resource\Foo', 'reqs' => ['bar' => '\d+']]
     *  ],
     * @see \Strapi\Router::add()
     *
     * The second argument is a callback that is invoked whenever a route is
     * matched.
     * @see \Strapi\Router::route()
     *
     * @param array    $routes
     * @throws \LogicException If a route definition exists without a "class"
     */
    public function __construct(array $routes)
    {
        foreach ($routes as $scheme => $route) {
            if (!isset($route['class'])) {
                throw new \LogicException('Route has no class: ' . $scheme);
            }
            $reqs = isset($route['reqs']) ? $route['reqs'] : [];
            $this->add($scheme, $route['class'], $reqs);
        }
    }

    /**
     * Adds a new route
     *
     * A route is identified by a unique scheme. Schemes are uri
     * representations that can optionally include required parameters.
     * These parameters are prefixed with a colon. Schemes may begin with a
     * required HTTP method, but if no method is specified, they will match
     * requests only by the URI.
     *
     * A route is mapped to a specific API resource class.
     *
     * If a route scheme includes required parameters, then parameter
     * requirements may be specified in the form of regular expressions.
     *
     *  e.g.
     *    - $this->add('POST /foo', 'Api\Resource\Foo');
     *    - $this->add('/bar/:id', 'Api\Resource\Bar', ['id' => '\d+']);
     *
     * @param string $scheme
     * @param string $class
     * @param array $requirements
	 * @return array
     */
    public function add($scheme, $class, array $requirements = [])
    {
        $restrictTo = null;
        $scheme = trim($scheme, ' ');
        if (($pos = strpos($scheme, ' ')) !== false) {
            $restrictTo = explode('|', strtoupper(substr($scheme, 0, $pos)));
            $scheme = substr($scheme, $pos + 1);
        }
		$scheme = trim($scheme, '/');

        $reqs = [];
        foreach ($requirements as $key => $regex) {
            $reqs[':' . $key] = $regex;
        }

        $this->routes[$scheme] = [
            'restrict_to' => $restrictTo,
            'class' => $class,
            'segments' => explode('/', $scheme),
            'reqs' => $reqs
        ];

		return $this->routes[$scheme];
    }

    /**
     * Finds the appropriate route for the given request and calls the loader
     *
     * The identified route is passes as the only argument to the loader, and
     * the result is returned immediately.
     *
     * @param \Strapi\Request $request
     * @return null|array
     */
    public function route($request)
    {
        $method = $request->method();
        $uri = trim($request->uri(), ' /');
        $segments = explode('/', $uri);

        foreach ($this->routes as $scheme => $route) {
            if ($route['restrict_to'] && !in_array($method, $route['restrict_to'])) {
                continue;
            }
            if ($scheme === $uri || $this->match($segments, $route)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Compares the given uri segments to the given route config
     *
     * @param array $segments
     * @param array $route
     * @return bool
     */
    protected function match(array $segments, array $route)
    {
        // if the route has no dynamic segments, then we can't compare it
        if (!isset($route['segments'])) {
            return false;
        }

        $current = current($route['segments']);
        foreach ($segments as $segment) {
            // can't be a match if our segment totals don't match up
            if ($current === false) {
                return false;
            }
            // static segments only need a straight up comparison
            if ($current[0] != ':' && $current != $segment) {
                return false;
            }
            // since this is confirmed to be a dynamic segment, we just ensure
            // it matches any regex validations the route specifies
            if (isset($route['reqs'][$current])) {
                $regex = $route['reqs'][$current];
                if (!preg_match("/^$regex$/i", $segment)) {
                    return false;
                }
            }
            $current = next($route['segments']);
        }

        // at this point, we know our uri matches all route segments that it
        // was compared to, but if $current is not false, then it means that
        // our uri actually had fewer segments than were required by the route,
        // so it cannot be a match
        return $current === false;
    }
}