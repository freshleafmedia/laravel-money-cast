<?php

use Freshleafmedia\MoneyCast\MoneyCast;

test('Serialises Money instances', function () {
    $cast = new MoneyCast();
    $money = new \Money\Money(100, new \Money\Currency('GBP'));

    expect($cast->set(null, 'cost', $money, []))
        ->toBeString()
        ->toBe('GBP100');
});

test('Serialises strings', function () {
    $cast = new MoneyCast();

    expect($cast->set(null, 'cost', 'GBP100', []))
        ->toBeString()
        ->toBe('GBP10000');
});

test('Serialisation handles null values', function () {
    $cast = new MoneyCast();

    expect($cast->set(null, 'cost', null, []))->toBeNull();
});

test('Un-serialises strings', function () {
    $cast = new MoneyCast();
    $money = 'GBP100';

    $moneyCast = $cast->get(null, 'cost', $money, []);

    expect($moneyCast)->toBeInstanceOf(\Money\Money::class);
    expect($moneyCast->getAmount())->toBe('100');
    expect($moneyCast->getCurrency()->getCode())->toBe('GBP');
});

test('Un-serialisation handles null values', function () {
    $cast = new MoneyCast();

    expect($cast->get(null, 'cost', null, []))->toBeNull();
});

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
]);
