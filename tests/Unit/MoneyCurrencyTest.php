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
