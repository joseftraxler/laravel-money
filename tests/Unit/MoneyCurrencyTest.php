<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use Tests\Examples\CurrencyEnum;
use Tests\Examples\CurrencyModelExample;

it('creates a Currency instance from currency string')
    ->expect(fn () => Currency::of('CZK'))
    ->toBeInstanceOf(Currency::class)
;

it('creates a Currency instance once')
    ->expect(fn () => Currency::of('CZK'))
    ->toBe(Currency::of('CZK'))
;

it('creates a Currency instance from Currencyable object')
    ->expect(fn () => Currency::of(CurrencyEnum::CZK))
    ->getCode()->toBe(CurrencyEnum::CZK->value)
;

it('creates a Currency instance from Currencyable model')
    ->expect(fn () => Currency::of(new CurrencyModelExample(['code' => 'CZK'])))
    ->getCode()->toBe('CZK')
;

it('creates case insensitive Currency instance')
    ->expect(fn () => Currency::of('Czk'))
    ->toBe(Currency::of('CZK'))
    ->getCode()->toBe('CZK')
;

it('returns true when comparing currencies with different cases')
    ->expect(fn () => Currency::of('CZK')->equals('czk'))
    ->toBeTrue()
;

it('reads precision from configured currencies', function () {
    \Illuminate\Support\Facades\App::make('config')->set('money.currencies.XAA.precision', 4);

    expect(Currency::of('XAA')->getPrecision())->toBe(4);
});

it('throws exception when configured currency precision is not an integer', function () {
    \Illuminate\Support\Facades\App::make('config')->set('money.currencies.XAB.precision', 'invalid');

    expect(fn () => Currency::of('XAB')->getPrecision())
        ->toThrow(\UnexpectedValueException::class);
});

it('throws exception when configured currency precision is negative', function () {
    \Illuminate\Support\Facades\App::make('config')->set('money.currencies.XAC.precision', -1);

    expect(fn () => Currency::of('XAC')->getPrecision())
        ->toThrow(\UnexpectedValueException::class);
});
