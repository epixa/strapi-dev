<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi;

/**
 * A middleware wrapper
 *
 * While middleware itself can be anything that is callable and that accepts
 * the next middleware as its first argument, this class is a callable wrapper
 * that provides a convenient way to access the "next" middleware without
 * having to pass around middleware references.
 *
 * Usage:
 *      $middleware = new Strapi\Middleware(function($next) {
 *          echo "first";
 *          next();
 *      });
 *      $middleware->next(new Strapi\Middleware(function(){
            echo "second";
 *      }));
 *      $middleware();
 */
class Middleware
{
    protected $callback;
    protected $next = null;

    /**
     * Constructs a new middleware wrapper for the given callback
     *
     * @param callable $callback
     * @throws \BadMethodCallException
     * @throws \InvalidArgumentException
     */
    public function __construct($callback)
    {
        if (!isset($callback)) {
            throw new \BadMethodCallException('Middleware must have a callback');
        }
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Provided middleware callback is not callable');
        }
        $this->callback = $callback;
    }

    /**
     * Returns the next middleware that should be invoked
     *
     * @param callable|null $next
     * @return callable|null
     * @throws \InvalidArgumentException
     */
    public function next($next = null)
    {
        if ($next !== null) {
            if (!is_callable($next)) {
                throw new \InvalidArgumentException('Provided "next" middleware callback is not callable');
            }
            $this->next = $next;
        }
        return $this->next;
    }

    /**
     * Proxies invokation of this wrapper to its callback
     *
     * If a "next" middleware exists, that is passed along as the only to the
     * callback as its only argument. If no "next" middleware exists, then an
     * empty callback is passed.
     *
     * This way middleware can require and even typehint for the next callback.
     */
    public function __invoke()
    {
        return call_user_func($this->callback, $this->next() ?: function(){});
    }
}