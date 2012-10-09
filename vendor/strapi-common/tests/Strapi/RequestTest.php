<?php

use FUnit\fu;

require_once __DIR__ . '/../autoload.php';

fu::setup(function() {
    $request = new Strapi\Request(['foo' => 'bar']);
    fu::fixture('request', $request);
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test('Environment variables', function(){
    $request = fu::fixture('request');
    fu::equal($request->env('foo'), 'bar', 'Environment variables from constructor are set correctly');

    $result = $request->env('foo', 'notbar');
    fu::strict_equal($result, 'notbar', 'Attempting to set env variable returns new value');

    $result = $request->env('foo');
    fu::strict_equal($result, 'notbar', 'Set env variable successfully');
});

fu::test('Acceptable media types', function(){
    $request = fu::fixture('request');

    fu::strict_equal($request->accept(), ['*/*'], 'Default accepted media range is "anything"');

    $in = 'text/html; q=1.0, text/*; q=0.8, image/gif; q=0.6, image/jpeg; q=0.6, image/*; q=0.5, */*; q=0.1';
    $expected = ['text/html', 'text/*', 'image/jpeg', 'image/gif', 'image/*', '*/*'];

    $out = $request->accept($in);
    fu::strict_equal($out, $expected, 'Attempting to set accept header returns parsed acceptable media types');

    $out = $request->accept();
    fu::strict_equal($out, $expected, 'Set/parsed accept header successfully');
});

fu::test('Request body', function(){
    $request = fu::fixture('request');
    fu::strict_equal($request->body(), null, 'Default body is null');
    fu::strict_equal($request->body('foo bar'), 'foo bar', 'Attempting to set body returns new value');
    fu::strict_equal($request->body(), 'foo bar', 'Set body successfully');
});

fu::test('Request method', function(){
    $request = fu::fixture('request');
    fu::strict_equal($request->method(), 'GET', 'Default method is GET');
    fu::strict_equal($request->method('PUT'), 'PUT', 'Attempting to set method returns new value');
    fu::strict_equal($request->method(), 'PUT', 'Set method successfully');
});

fu::test('Request params', function(){
    $request = fu::fixture('request');
    fu::strict_equal($request->params(), [], 'Default params is an empty array');
    fu::strict_equal($request->params(['foo' => 'bar']), ['foo' => 'bar'], 'Attempting to set params returns new value');
    fu::strict_equal($request->params(), ['foo' => 'bar'], 'Set params successfully');
});

fu::test('Request content type', function(){
    $request = fu::fixture('request');
    $type = 'application/x-www-form-urlencoded';
    fu::strict_equal($request->type(), $type, 'Default content type is ' . $type);
    fu::strict_equal($request->type('application/json'), 'application/json', 'Attempting to set type returns new value');
    fu::strict_equal($request->type(), 'application/json', 'Set content type successfully');
    fu::strict_equal($request->type('notvalid'), 'application/json', 'Trying to set invalid type does nothing');
});

fu::test('Request uri', function(){
    $request = fu::fixture('request');
    fu::strict_equal($request->uri(), '/', 'Default uri is /');
    fu::strict_equal($request->uri('/foo/bar'), '/foo/bar', 'Attempting to set uri returns new value');
    fu::strict_equal($request->uri(), '/foo/bar', 'Set uri successfully');
});

fu::run();
