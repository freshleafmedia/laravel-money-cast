<?php

use Freshleafmedia\MoneyCast\MoneyCast;
use Money\Currency;
use Money\Money;

test('Serialises values', function ($value, $expected) {
    $cast = new MoneyCast();

    expect($cast->set(null, 'cost', $value, []))->toBe($expected);
})
->with([
    [new Money(100, new Currency('GBP')), 'GBP100'],
    [new Money(-100, new Currency('GBP')), 'GBP-100'],
    ['GBP100', 'GBP10000'],
    ['GBP-100', 'GBP-10000'],
    [null, null],
]);

test('Un-serialises values', function ($value, $expected) {
    $cast = new MoneyCast();

    $moneyCast = $cast->get(null, 'cost', $value, []);

    expect($moneyCast)->toEqual($expected);
})
->with([
    ['GBP100', new Money(100, new Currency('GBP'))],
    ['GBP-100', new Money(-100, new Currency('GBP'))],
    [null, null],
]);

test('Un-serialisation throws on malformed values', function ($malformedValue) {
    $cast = new MoneyCast();

    $cast->get(null, 'cost', $malformedValue, []);
})
->throws(Exception::class)
->with([
    'GB100',
    '',
    'GPB',
    '100',
    '-EUR1337',
]);
