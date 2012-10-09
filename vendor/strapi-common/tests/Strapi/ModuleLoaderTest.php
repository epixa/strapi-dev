<?php

use FUnit\fu;

require_once __DIR__ . '/../autoload.php';

fu::setup(function() {
    fu::fixture('loader', new StrapiMock\ModuleLoader());
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test("Module path setting and retrieving", function() {
    $loader = fu::fixture('loader');
    fu::strict_equal($loader->modulePath(), '', 'Module path is defaulted to an empty string');

    $loader->modulePath(__DIR__);
    fu::strict_equal(__DIR__, $loader->modulePath(), 'Valid module path is set');

    fu::ok(call_user_func(function() use ($loader){
        try {
            $ds = DIRECTORY_SEPARATOR;
            $loader->modulePath(__DIR__ . $ds . implode($ds, ['garbage', 'path']));
            return false;
        } catch (RuntimeException $e) {
            return true;
        }
    }), 'Attempting to set module path to a non-existent path throws RuntimeException');

    fu::ok(call_user_func(function() use ($loader){
        try {
            $loader->modulePath(__FILE__);
            return false;
        } catch (RuntimeException $e) {
            return true;
        }
    }), 'Attempting to set module path to a non-directory throws RuntimeException');
});

fu::test('Module loading', function(){
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'StrapiMock';
    $loader = fu::fixture('loader');
    $loader->modulePath($path);

    $counter = $loader->load($path . '/modules/counter');
    fu::ok(is_callable($counter), 'Valid module loaded via absolute path');

    $counter = $loader->load('modules/counter');
    fu::strict_equal($counter(), 1, 'Valid module relative to module path is loaded');

    $counter = $loader->load('modules/counter');
    fu::strict_equal($counter(), 2, 'Existing module is returned when it has already been loaded once');

    fu::ok(call_user_func(function() use ($loader){
        try {
            $loader->load('garbage/module');
            return false;
        } catch (RuntimeException $e) {
            return true;
        }
    }), 'Attempting to load a module that does not exist throws a RuntimeException');
});

fu::run();
