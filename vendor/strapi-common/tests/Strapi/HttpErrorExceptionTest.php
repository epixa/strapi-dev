<?php

use \FUnit\fu;

require_once __DIR__ . '/../autoload.php';

fu::test("Confirm detaults", function() {
    $e = new Strapi\HttpErrorException();

    fu::strict_equal($e->getCode(), 400, 'Has default code of 400');
    fu::strict_equal($e->getMessage(), '', 'Has default message of ""');
    fu::ok($e instanceof Exception, 'Is instance of exception');
});

fu::test("Confirm successful proxy to parent exception", function() {
    $previous = new \Exception('prev', 1);
    $e = new Strapi\HttpErrorException('Not found', 404, $previous);

    fu::strict_equal($e->getCode(), 404, 'Properly set code through constructor');
    fu::strict_equal($e->getMessage(), 'Not found', 'Properly set message through constructor');
    fu::strict_equal($e->getPrevious(), $previous, 'Properly set previous through constructor');
});

fu::run();
