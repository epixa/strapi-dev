<?php

use FUnit\fu;

require_once __DIR__ . '/../autoload.php';

fu::test("Middleware instantiation", function() {
    fu::ok(function(){
        try {
            new Strapi\Middleware();
            return false;
        } catch (BadMethodCallException $e) {
            return true;
        }
    }, 'BadMethodCallException thrown when no callback is passed to constructor');

    // tests bool, int, float, array, object
    foreach ([true,1,1.0,[],new \stdClass()] as $type) {
        fu::ok(call_user_func(function() use ($type){
            try {
                new Strapi\Middleware($type);
                return false;
            } catch (InvalidArgumentException $e) {
                return true;
            }
        }), 'InvalidArgumentException thrown when ' . gettype($type) . ' is passed instead of callback');
    }

    $invokable = new StrapiMock\InvokableClass();
    fu::ok(new Strapi\Middleware($invokable), 'Invokable object callback');
    fu::ok(new Strapi\Middleware([$invokable, '__invoke']), 'Array object callback');
    fu::ok(new Strapi\Middleware(['FUnit\\fu', 'test']), 'Array static callback');
    fu::ok(new Strapi\Middleware('FUnit\\fu::test'), 'String callback');
    fu::ok(new Strapi\Middleware(function(){}), 'Anonymous function callback');
});

fu::test("Setting and retrieving next middleware", function(){
    $middleware = new Strapi\Middleware(function(){});
    fu::strict_equal($middleware->next(), null, 'No next middleware by default');

    // tests bool, int, float, array, object
    foreach ([true,1,1.0,[],new \stdClass()] as $type) {
        fu::ok(call_user_func(function() use ($middleware, $type){
            try {
                $middleware->next($type);
                return false;
            } catch (InvalidArgumentException $e) {
                return true;
            }
        }), 'InvalidArgumentException thrown when ' . gettype($type) . ' is passed instead of callback');
    }

    $middleware->next(function(){});
    fu::ok(is_callable($middleware->next()), 'Next middleware set properly when passed valid callback');
});

fu::test("Invoking middleware object as a function", function(){
    $middleware = new Strapi\Middleware(function($next){
        return is_callable($next);
    });
    $result = call_user_func($middleware);
    fu::ok($result, '$next is defaulted to a function if not set explicitly');

    $middleware = new Strapi\Middleware(function($next){
        return $next();
    });
    $middleware->next(function(){
        return 'foo';
    });
    fu::strict_equal(call_user_func($middleware), 'foo', 'Next middleware is properly passed as $next');
});

fu::run();
