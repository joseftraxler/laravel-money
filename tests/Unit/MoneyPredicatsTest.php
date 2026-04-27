<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

$eur = Currency::of('EUR');

it('returns true for isPositive when amount is positive')
    ->assertTrue((new Money(1, $eur))->isPositive());

it('returns false for isZero when amount is positive')
    ->assertFalse((new Money(1, $eur))->isZero());

it('returns false for isNegative when amount is positive')
    ->assertFalse((new Money(1, $eur))->isNegative());

it('returns true for isNegative when amount is negative')
    ->assertTrue((new Money(-1, $eur))->isNegative());

it('returns false for isZero when amount is negative')
    ->assertFalse((new Money(-1, $eur))->isZero());

it('returns false for isPositive when amount is negative')
    ->assertFalse((new Money(-1, $eur))->isPositive());

it('returns true for isZero when amount is zero')
    ->assertTrue((new Money(0, $eur))->isZero());

it('returns false for isPositive when amount is zero')
    ->assertFalse((new Money(0, $eur))->isPositive());

it('returns false for isNegative when amount is zero')
    ->assertFalse((new Money(0, $eur))->isNegative());
