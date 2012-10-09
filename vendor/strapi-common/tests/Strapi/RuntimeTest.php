<?php

use FUnit\fu;

require_once __DIR__ . '/../autoload.php';

fu::setup(function() {
    $ds = DIRECTORY_SEPARATOR;
    $modulePath = realpath(__DIR__ . $ds . implode($ds, ['..', 'StrapiMock', 'modules']));
    fu::fixture('runtime', new Strapi\Runtime($modulePath));
    fu::fixture('modulePath', $modulePath);
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test('Module loader path setting through construction', function(){
    $modulePath = fu::fixture('modulePath');
    var_dump($modulePath);
    $runtime = new Strapi\Runtime($modulePath);
    fu::equal($runtime->modulePath(), $modulePath, 'Module path is set during construction and retrieved correctly');
});

fu::test("Module loader path setting and retrieving through modulePath()", function() {
    $runtime = fu::fixture('runtime');

    $runtime->modulePath(__DIR__);
    fu::strict_equal(__DIR__, $runtime->modulePath(), 'Valid module path is set');

    fu::ok(call_user_func(function() use ($runtime){
        try {
            $ds = DIRECTORY_SEPARATOR;
            $runtime->modulePath(__DIR__ . $ds . implode($ds, ['garbage', 'path']));
            return false;
        } catch (RuntimeException $e) {
            return true;
        }
    }), 'Attempting to set module path to a non-existent path throws RuntimeException');

    fu::ok(call_user_func(function() use ($runtime){
        try {
            $runtime->modulePath(__FILE__);
            return false;
        } catch (RuntimeException $e) {
            return true;
        }
    }), 'Attempting to set module path to a non-directory throws RuntimeException');
});

fu::test('Module loading through load()', function(){
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'StrapiMock';
    $runtime = fu::fixture('runtime');
    $runtime->modulePath($path);

    $counter = $runtime->load($path . '/modules/counter');
    fu::ok(is_callable($counter), 'Valid module loaded via absolute path');

    $counter = $runtime->load('modules/counter');
    fu::strict_equal($counter(), 1, 'Valid module relative to module path is loaded');

    $counter = $runtime->load('modules/counter');
    fu::strict_equal($counter(), 2, 'Existing module is returned when it has already been loaded once');

    fu::ok(call_user_func(function() use ($runtime){
        try {
            $runtime->load('garbage/module');
            return false;
        } catch (RuntimeException $e) {
            return true;
        }
    }), 'Attempting to load a module that does not exist throws a RuntimeException');
});

fu::test('Invoking runtime on a non-existent url', function(){
    $runtime = fu::fixture('runtime');
    $runtime->load('request')->uri('/404');
    fu::ok(call_user_func(function() use ($runtime){
        try {
            call_user_func($runtime);
            return false;
        } catch (Strapi\HttpErrorException $e) {
            return true;
        }
    }), 'Invoking the runtime on an invalid url throws Strapi\HttpErrorException');
});

fu::test('Invoking runtime on an existent url', function(){
    $runtime = fu::fixture('runtime');
    $result = call_user_func($runtime);
    fu::equal($result, 'ok', 'Invoking the runtime on a valid url sends off to dispatcher module');
});

fu::test('Running without additional middleware', function(){
    $runtime = fu::fixture('runtime');
    $result = $runtime->run();
    fu::equal($result, 'ok', 'Running the runtime without middleware dispatches correctly');
});

fu::run();
