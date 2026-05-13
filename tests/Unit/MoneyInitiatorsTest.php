<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

it('creates new Money correctly with int value')
    ->expect(fn () => new Money(600, Currency::of('EUR')))
    ->getDecimal()->toBe('600.00')
    ->getCents()->toBe('60000')
    ->getCurrency()->toBe(Currency::of('EUR'))
    ->multiply('2.5')->getDecimal()->toBe('1500.00');

it('creates new Money correctly with float value')
    ->expect(fn () => new Money(0.1, Currency::of('EUR')))
    ->getDecimal()->toBe('0.10')
    ->getCents()->toBe('10')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly with 0 value')
    ->expect(fn () => new Money(0, Currency::of('EUR')))
    ->getDecimal()->toBe('0.00')
    ->getCents()->toBe('0')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly with string value')
    ->expect(fn () => new Money('600.50', Currency::of('EUR')))
    ->getDecimal()->toBe('600.50')
    ->getCents()->toBe('60050')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly with "0" value')
    ->expect(fn () => new Money('0', Currency::of('EUR')))
    ->getDecimal()->toBe('0.00')
    ->getCents()->toBe('0')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly with plus sign value')
    ->expect(fn () => new Money('+600', Currency::of('EUR')))
    ->getDecimal()->toBe('600.00')
    ->getCents()->toBe('60000')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly with negative int value')
    ->expect(fn () => new Money(-200, Currency::of('EUR')))
    ->getDecimal()->toBe('-200.00')
    ->getCents()->toBe('-20000')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly with negative float value')
    ->expect(fn () => new Money(-200.65, Currency::of('EUR')))
    ->getDecimal()->toBe('-200.65')
    ->getCents()->toBe('-20065')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly with negative string value')
    ->expect(fn () => new Money('-600.5', Currency::of('EUR')))
    ->getDecimal()->toBe('-600.50')
    ->getCents()->toBe('-60050')
    ->getCurrency()->toBe(Currency::of('EUR'));

it('creates new Money correctly by fromDecimal method')
    ->expect(fn () => Money::fromDecimal('600.50', Currency::of('EUR')))
        ->getDecimal()->toBe('600.50')
        ->getCurrency()->toBe(Currency::of('EUR'))
    ->and(fn () => Money::fromDecimal(300, Currency::of('USD')))
        ->getDecimal()->toBe('300.00')
        ->getCurrency()->toBe(Currency::of('USD'))
    ->and(fn () => Money::fromDecimal(0.1, Currency::of('EUR')))
        ->getDecimal()->toBe('0.10')
        ->getCurrency()->toBe(Currency::of('EUR'))
;

it('creates new Money correctly by fromCents method')
    ->expect(fn () => Money::fromCents('60050', Currency::of('EUR')))
    ->getCents()->toBe('60050')
    ->getCurrency()->toBe(Currency::of('EUR'))
    ->and(fn () => Money::fromCents(30000, Currency::of('USD')))
    ->getCents()->toBe('30000')
    ->getCurrency()->toBe(Currency::of('USD'))
    ->and(fn () => Money::fromCents(10, Currency::of('EUR')))
    ->getCents()->toBe('10')
    ->getCurrency()->toBe(Currency::of('EUR'))
;
it('creates new Money using static magic method by currency code')
    ->expect(fn () => Money::EUR('600.50'))
        ->getDecimal()->toBe('600.50')
        ->getCurrency()->toBe(Currency::of('EUR'))
    ->and(fn () => Money::USD(300))
        ->getDecimal()->toBe('300.00')
        ->getCurrency()->toBe(Currency::of('USD'))
    ->and(fn () => Money::EUR(0.1))
        ->getDecimal()->toBe('0.10')
        ->getCurrency()->toBe(Currency::of('EUR'))
    ->and(fn () => Money::USD(0))
        ->getDecimal()->toBe('0.00')
        ->getCurrency()->toBe(Currency::of('USD'))
;

it('creates new Money using static magic method by currency code with zero decimal')
    ->expect(fn () => Money::EUR(0))
        ->getDecimal()->toBe('0.00')
        ->getCurrency()->toBe(Currency::of('EUR'))
    ->and(fn () => Money::USD(0))
        ->getDecimal()->toBe('0.00')
        ->getCurrency()->toBe(Currency::of('USD'))
;

it('creates new Money with negative decimal')
    ->expect(fn () => Money::EUR('-0.50'))
        ->getDecimal()->toBe('-0.50')
        ->getCents()->toBe('-50')
        ->getCurrency()->toBe(Currency::of('EUR'))
    ->and(fn () => Money::EUR('-0.05'))
        ->getDecimal()->toBe('-0.05')
        ->getCents()->toBe('-5')
        ->getCurrency()->toBe(Currency::of('EUR'))
    ->and(fn () => Money::EUR('-0.00'))
        ->getDecimal()->toBe('0.00')
        ->getCents()->toBe('0')
        ->getCurrency()->toBe(Currency::of('EUR'))
;
