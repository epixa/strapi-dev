<?php

use \FUnit\fu;

require_once __DIR__ . '/../../autoload.php';

fu::setup(function() {
    fu::fixture('parser', new Strapi\BodyParser\Json());
});

fu::teardown(function() {
    fu::reset_fixtures();
});

fu::test("Json encoding through to()", function() {
    $parser = fu::fixture('parser');

    $out = $parser->to(1);
    $expected = '1';
    fu::strict_equal($expected, $out, 'Integer input');

    $out = $parser->to('foo');
    $expected = '"foo"';
    fu::strict_equal($expected, $out, 'String input');

    $out = $parser->to([1,2,3]);
    $expected = '[1,2,3]';
    fu::strict_equal($expected, $out, 'Numeric array input');

    $out = $parser->to(['foo' => 'bar', 'one' => 'two']);
    $expected = '{"foo":"bar","one":"two"}';
    fu::strict_equal($expected, $out, 'Associative array input');

    $out = $parser->to(['foo' => 'bar', 1, 'two']);
    $expected = '{"foo":"bar","0":1,"1":"two"}';
    fu::strict_equal($expected, $out, 'Mixed array input');
});

fu::test("Json decoding through from()", function() {
    $parser = fu::fixture('parser');

    $out = $parser->from('1');
    $expected = 1;
    fu::strict_equal($expected, $out, 'Integer input');

    $out = $parser->from('"foo"');
    $expected = 'foo';
    fu::strict_equal($expected, $out, 'String input');

    $out = $parser->from('[1,2,3]');
    $expected = [1,2,3];
    fu::strict_equal($expected, $out, 'Numeric array input');

    $out = $parser->from('{"foo":"bar","one":"two"}');
    $expected = ['foo' => 'bar', 'one' => 'two'];
    fu::strict_equal($expected, $out, 'Associative array input');

    $out = $parser->from('{"foo":"bar","0":1,"1":"two"}');
    $expected = ['foo' => 'bar', 1, 'two'];
    fu::strict_equal($expected, $out, 'Mixed array input');
});

fu::run();
