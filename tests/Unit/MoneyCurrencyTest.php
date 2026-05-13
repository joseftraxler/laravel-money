<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use Tests\Examples\TestExampleCurrencyEnum;
use Tests\Examples\TestExampleCurrencyModel;

it('creates a Currency instance from currency string')
    ->expect(fn () => Currency::of('CZK'))
    ->toBeInstanceOf(Currency::class)
;

it('creates a Currency instance once')
    ->expect(fn () => Currency::of('CZK'))
    ->toBe(Currency::of('CZK'))
;

it('creates a Currency instance from Currencyable object')
    ->expect(fn () => Currency::of(TestExampleCurrencyEnum::CZK))
    ->getCode()->toBe(TestExampleCurrencyEnum::CZK->value)
;

it('creates a Currency instance from Currencyable model')
    ->expect(fn () => Currency::of(new TestExampleCurrencyModel(['code' => 'CZK'])))
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

it('uses default precision detection')
    ->expect(Currency::of('JPY')->getPrecision())
    ->toBe(0)
    ->and(Currency::of('BHD')->getPrecision())
    ->toBe(3)
    ->and(Currency::of('EUR')->getPrecision())
    ->toBe(2);
