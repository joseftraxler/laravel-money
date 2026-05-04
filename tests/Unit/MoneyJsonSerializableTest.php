<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

it('serializes money object to JSON')
    ->expect(fn () => json_encode(Money::USD(100.5)))
    ->toBe('{"decimal":"100.50","currency":"USD"}')
;

it('deserializes money object from JSON')
    ->expect(fn () => Money::fromJson('{"decimal":"100.50","currency":"USD"}'))
    ->toBeInstanceOf(Money::class)
    ->getDecimal()->toBe('100.50')
    ->getCurrency()->toBe(Currency::of('USD'))
;
