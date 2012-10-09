<?php

use FUnit\fu;

require_once __DIR__ . '/../autoload.php';

fu::setup(function() {
    $router = new Strapi\Router([
        'foo/:bar' => ['class' => 'StrapiMock\MockResource', 'reqs' => ['bar' => '\d+']],
        'foo' => ['class' => 'StrapiMock\MockResource']
    ]);
    fu::fixture('router', $router);
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test('Router constructor', function(){
    fu::ok(call_user_func(function() {
        try {
            new Strapi\Router([1]);
            return false;
        } catch (LogicException $e) {
            return true;
        }
    }), 'Attempting to configure router with a route that has no class defined throws a LogicException');
});

fu::test('Adding routes', function(){
    $router = fu::fixture('router');
    $route = $router->add('one/:two', 'StrapiMock\MockResource', ['two' => '\d+']);
    $expected = [
        'restrict_to' => null,
        'class' => 'StrapiMock\MockResource',
        'segments' => ['one', ':two'],
        'reqs' => [':two' => '\\d+']
    ];
    fu::strict_equal($route, $expected, 'Attempting to add a route returns parsed route data');

    $route = $router->add('one/two/:three', 'StrapiMock\MockResource');
    $expected = [
        'restrict_to' => null,
        'class' => 'StrapiMock\MockResource',
        'segments' => ['one', 'two', ':three'],
        'reqs' => []
    ];
    fu::strict_equal($route, $expected, 'Successfully added route without any requirements');

    $route = $router->add('one', 'StrapiMock\MockResource');
    $expected = [
        'restrict_to' => null,
        'class' => 'StrapiMock\MockResource',
        'segments' => ['one'],
        'reqs' => []
    ];
    fu::strict_equal($route, $expected, 'Successfully added a static route (no parameters)');

    $route = $router->add('POST /foo/bar', 'StrapiMock\MockResource');
    $expected = [
        'restrict_to' => ['POST'],
        'class' => 'StrapiMock\MockResource',
        'segments' => ['foo', 'bar'],
        'reqs' => []
    ];
    fu::strict_equal($route, $expected, 'Successfully added a route restricted to one method');
});

fu::test('Routing requests', function(){
    $router = fu::fixture('router');
    $request = new \StrapiMock\MockRequest([]);

    $request->uri('/foo');
    $route = $router->route($request);
    $expected = [
      'restrict_to' => null,
      'class' => 'StrapiMock\MockResource',
      'segments' => ["foo"],
      'reqs' => []
    ];
    fu::strict_equal($route, $expected, 'Successfully matched a static route');

    $request->uri('/foo/1');
    $route = $router->route($request);
    $expected = [
      'restrict_to' => null,
      'class' => 'StrapiMock\MockResource',
      'segments' => ['foo', ':bar'],
      'reqs' => [':bar' => '\d+']
    ];
    fu::strict_equal($route, $expected, 'Successfully matched non-static route');

    $request->uri('/does/not/exist');
    $route = $router->route($request);
    fu::strict_equal($route, null, 'Attempting to find a route for a non-existent resource returned null route');

    $request->uri('/foo/not-integer');
    $route = $router->route($request);
    fu::strict_equal($route, null, 'Attempting to find a route that does not match requirements returned null route');
});

fu::run();