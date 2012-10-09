<?php

use \FUnit\fu;

require_once __DIR__ . '/../../autoload.php';

fu::setup(function() {
    fu::fixture('parser', new StrapiMock\BodyParser\MockParser());
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test("__invoke() proxying to from() and to() based on input type", function() {
    $parser = fu::fixture('parser');

    // tests string
    $out = call_user_func($parser, 'foo');
    $expected = ['StrapiMock\BodyParser\MockParser::from', 'foo'];
    fu::strict_equal($expected, $out, 'Proxied to from() when given a string');

    // tests bool, int, float, array, object
    foreach ([true,1,1.0,[],new \stdClass(),null] as $type) {
        $out = call_user_func($parser, $type);
        $expected = ['StrapiMock\BodyParser\MockParser::to', $type];
        fu::strict_equal($expected, $out, 'Proxied to to() when given a ' . gettype($type));
    }
});

fu::run();
