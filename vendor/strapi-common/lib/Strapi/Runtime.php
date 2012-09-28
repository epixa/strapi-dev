<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi;

/**
 * The Strapi runtime
 *
 * A Strapi API is simply a set of modules that can be loaded via its
 * ModuleLoader as well as the means to "run" those modules as a REST api.
 *
 * The only required modules are 'request', 'router' and 'middleware', but
 * since each module is executed within the scope of the Strapi ModuleLoader,
 * more modules will likely be lazy-loaded as necessary.
 *
 * Modules are PHP files located within the provided project root. They are
 * identified by their path relative to the root, and they are only included
 * on the first call. The result of the module initiation is stored in the
 * registry and is returned on the current and all subsequent calls to load
 * this module.
 *
 * @see \Strapi\ModuleLoader
 */
class Runtime
{
    use ModuleLoader;

    /**
     * Constructs a new Strapi runtime
     *
     * A runtime only requires a specific directory to act as the root of the
     * API project.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->modulePath($path);
    }

    /**
     * Invokes the strapi runtime
     *
     * This loads the request module, and passes it to the route() method of
     * the router module.  Finally, it sends the response.
     */
    public function __invoke()
    {
        /* @var $request \Strapi\Request */
        $request = $this->load('request');

        if ($route = $this->load('router')->route($request)) {
            call_user_func($this->load('dispatcher'), $route);
        } else {
            throw new HttpErrorException('Route not found: ' . $request->uri(), 404);
        }
    }

    /**
     * Runs the Strapi api
     *
     * This will load the middleware module, register itself as the last
     * middleware component, and then execute the middleware chain. When all
     * of the other middleware has run successfully, the entire runtime gets
     * invoked, which ticks of the request routing and response sending.
     */
    public function run()
    {
        /* @var $current callable */
        /* @var $middleware array */
        $middleware = $this->load('middleware') ?: [];
        if ($last = array_pop($middleware)) {
            $middleware[] = $last;
            $last->next($this);
        }
        $middleware[] = $this;

        call_user_func(current($middleware));
    }
}
