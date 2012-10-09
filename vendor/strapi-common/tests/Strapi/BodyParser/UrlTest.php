<?php

use \FUnit\fu;

require_once __DIR__ . '/../../autoload.php';

fu::setup(function() {
    fu::fixture('parser', new Strapi\BodyParser\Url());
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test("Direct url query encoding through to()", function() {
    $parser = fu::fixture('parser');

    $out = $parser->to([1,2,3]);
    $expected = '0=1&1=2&2=3';
    fu::strict_equal($expected, $out, 'Numeric array input');

    $out = $parser->to(['foo' => 'bar', 'one' => 'two']);
    $expected = 'foo=bar&one=two';
    fu::strict_equal($expected, $out, 'Associative array input');

    $out = $parser->to(['foo' => 'bar', 1, 'two']);
    $expected = 'foo=bar&0=1&1=two';
    fu::strict_equal($expected, $out, 'Mixed array input');

    fu::ok(call_user_func(function() use ($parser){
        try {
            $parser->to('nooooo');
            return false;
        } catch (InvalidArgumentException $e) {
            return true;
        }
    }), 'Trying to convert a non-array with from() threw InvalidArgumentException');
});

fu::test("Direct url query decoding through from()", function() {
    $parser = fu::fixture('parser');

    $out = $parser->from('0=1&1=2&2=3');
    $expected = ['1','2','3'];
    fu::strict_equal($expected, $out, 'Numeric array input');

    $out = $parser->from('foo=bar&one=two');
    $expected = ['foo' => 'bar', 'one' => 'two'];
    fu::strict_equal($expected, $out, 'Associative array input');

    $out = $parser->from('foo=bar&0=1&1=two');
    $expected = ['foo' => 'bar', '1', 'two'];
    fu::strict_equal($expected, $out, 'Mixed array input');
});

fu::run();
