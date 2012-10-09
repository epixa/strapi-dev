<?php

use FUnit\fu;

require_once __DIR__ . '/../autoload.php';

fu::setup(function() {
    $response = new Strapi\Response();
    fu::fixture('response', $response);
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test('Response constructor and reset', function(){
    $response = new Strapi\Response(404, 'foobar', ['foo' => 'bar']);
    fu::strict_equal($response->body(), 'foobar', 'Set response body through constructor');
    fu::strict_equal($response->status(), 404, 'Set response status through constructor');
    $headers = ['status' => 'HTTP/1.1 404 Not Found', 'foo' => 'foo: bar'];
    fu::strict_equal($response->headers(), $headers, 'Set response headers through constructor');

    $compare = new Strapi\Response(400, 'newbody', ['new' => 'header']);
    $response->reset(400, 'newbody', ['new' => 'header']);
    fu::equal($compare, $response, 'Reset response to new values');

    $compare = fu::fixture('response');
    $response->reset();
    fu::equal($compare, $response, 'Calling reset without arguments resets back to defaults');
});

fu::test('Response body', function(){
    $response = fu::fixture('response');
    fu::strict_equal($response->body(), null, 'Default response body is null');
    fu::strict_equal($response->body('foo'), 'foo', 'Attempting to set the body returns new value');
    fu::strict_equal($response->body(), 'foo', 'Set body successfully');
});

fu::test('Response headers', function(){
    $response = fu::fixture('response');
    fu::strict_equal($response->headers(), ['status' => 'HTTP/1.1 200 OK'], 'Default headers only have a status header');

    $headers = ['status' => 'HTTP/1.1 200 OK', 'foo' => 'foo: bar'];
    fu::strict_equal($response->headers(['foo' => 'bar']), $headers, 'Attempting to set headers returns updated headers');
    fu::strict_equal($response->headers(), $headers, 'Set headers successfully');

    fu::strict_equal($response->header('foo', 'notbar'), 'foo: notbar', 'Attempting to set a header returns the raw header');
    fu::strict_equal($response->header('foo'), 'foo: notbar', 'Set specific header successfully');
    fu::strict_equal($response->header('foo', 'bar', false), 'foo: notbar, bar', 'Set specific header without replacing');
});

fu::test('Response status', function(){
    $response = fu::fixture('response');
    $response->header('blah', 'ok');
    fu::strict_equal($response->status(), 200, 'Default response status is 200');
    fu::strict_equal($response->status(400), 400, 'Attempting to set response status returns new status');
    fu::strict_equal($response->status(), 400, 'Set response status successfully');
    fu::strict_equal($response->header('status'), 'HTTP/1.1 400 Bad Request', 'Setting status updates status header');
    fu::strict_equal($response->header('blah'), 'blah: ok', 'Setting status header does not override other headers');
});

fu::run();
