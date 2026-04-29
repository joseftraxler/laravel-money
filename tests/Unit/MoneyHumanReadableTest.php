<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Money;

function nbsp(string $string): string
{
    return str_replace(' ', "\u{00A0}", $string);
}

it('prints human readable money')
    ->expect(fn () => Money::EUR(100))
        ->toHumanReadable()->toBe('€100.00')
        ->toHumanReadableAmount()->toBe('100.00')
        ->toHumanReadableCurrency()->toBe('€')
    ->expect(fn () => Money::JPY(100))
        ->toHumanReadable()->toBe('¥100')
        ->toHumanReadableAmount()->toBe('100')
        ->toHumanReadableCurrency()->toBe('¥')
    ->expect(fn () => Money::USD(0))
        ->toHumanReadable()->toBe('$0.00')
        ->toHumanReadableAmount()->toBe('0.00')
        ->toHumanReadableCurrency()->toBe('$')
    ->expect(fn () => Money::CZK(0))
        ->toHumanReadable()->toBe(nbsp("CZK 0.00"))
        ->toHumanReadableAmount()->toBe('0.00')
        ->toHumanReadableCurrency()->toBe('CZK')
;

it('prints human readable money in specific locale')
    ->expect(fn () => Money::EUR(100))
        ->toHumanReadable('cs_CZ')->toBe(nbsp("100,00 €"))
        ->toHumanReadableAmount('cs_CZ')->toBe('100,00')
        ->toHumanReadableCurrency('cs_CZ')->toBe('€')
    ->expect(fn () => Money::JPY(100))
        ->toHumanReadable('cs_CZ')->toBe(nbsp("100 JP¥"))
        ->toHumanReadableAmount('cs_CZ')->toBe('100')
        ->toHumanReadableCurrency('cs_CZ')->toBe('JP¥')
    ->expect(fn () => Money::USD(0))
        ->toHumanReadable('cs_CZ')->toBe(nbsp("0,00 US$"))
        ->toHumanReadableAmount('cs_CZ')->toBe('0,00')
        ->toHumanReadableCurrency('cs_CZ')->toBe('US$')
    ->expect(fn () => Money::CZK(0))
        ->toHumanReadable('cs_CZ')->toBe(nbsp("0,00 Kč"))
        ->toHumanReadableAmount('cs_CZ')->toBe('0,00')
        ->toHumanReadableCurrency('cs_CZ')->toBe('Kč')
    ->expect(fn () => Money::CZK(1234567.89))
        ->toHumanReadable('cs_CZ')->toBe(nbsp("1 234 567,89 Kč"))
        ->toHumanReadableAmount('cs_CZ')->toBe(nbsp("1 234 567,89"))
        ->toHumanReadable('cs')->toBe(nbsp("1 234 567,89 Kč"))
        ->toHumanReadableAmount('cs')->toBe(nbsp("1 234 567,89"))
        ->toHumanReadable('en')->toBe(nbsp("CZK 1,234,567.89"))
        ->toHumanReadableAmount('en')->toBe(nbsp("1,234,567.89"))
    ->expect(fn () => Money::KRW(-1234567))
        ->toHumanReadable('ko_KR')->toBe(nbsp("-₩1,234,567"))
        ->toHumanReadableAmount('ko_KR')->toBe(nbsp("-1,234,567"))
;
