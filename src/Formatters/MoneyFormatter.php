<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Formatters;

use Illuminate\Support\Facades\App;
use JosefTraxler\LaravelMoney\Contracts\Currencyable;
use JosefTraxler\LaravelMoney\Contracts\Money;
use NumberFormatter;

class MoneyFormatter implements \JosefTraxler\LaravelMoney\Contracts\MoneyFormatter
{
    public function humanReadableAmount(Money $money, ?string $locale = null): string
    {
        return self::decimalFormatter(
            $locale,
            fn (\NumberFormatter $formatter) => $formatter->setAttribute(
                \NumberFormatter::FRACTION_DIGITS,
                $money->getCurrency()->getPrecision(),
            ),
        )->format(
            (float) $money->getDecimal(),
        ) ?: $money->getDecimal();
    }

    public function humanReadableCurrency(Currencyable|Money $currencyOrMoney, ?string $locale = null): string
    {
        /** @var array<string, string> $cache */
        static $cache = [];

        $money = $currencyOrMoney instanceof Money
            ? $currencyOrMoney
            : new \JosefTraxler\LaravelMoney\Money(0, $currencyOrMoney);

        return $cache[$locale . '-' . $money->getCurrency()->getCode()] ??= self::currencyFormatter(
            $locale,
            fn (\NumberFormatter $formatter) => $formatter->setTextAttribute(
                \NumberFormatter::CURRENCY_CODE,
                $money->getCurrency()->getCode(),
            )
        )->getSymbol(NumberFormatter::CURRENCY_SYMBOL) ?: $money->getCurrency()->getCode();
    }

    public function humanReadable(Money $money, ?string $locale = null): string
    {
        return (string) self::currencyFormatter(
            $locale,
            fn (\NumberFormatter $formatter) => $formatter->setAttribute(
                \NumberFormatter::FRACTION_DIGITS,
                $money->getCurrency()->getPrecision(),
            ),
        )->formatCurrency(
            (float) $money->getDecimal(),
            $money->getCurrency()->getCode(),
        ) ?: $money->getCurrency()->getCode() . "\u{00A0}" . $money->getDecimal();
    }

    protected static function currencyFormatter(?string $locale = null, ?\Closure $initFormatter = null): \NumberFormatter
    {
        /** @var array<\NumberFormatter> $formatters */
        static $formatters = [];
        $locale ??= App::getLocale();
        $formatter = $formatters[$locale] ??= new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        if ($initFormatter) {
            $initFormatter($formatter);
        }
        return $formatter;
    }

    protected static function decimalFormatter(?string $locale = null, ?\Closure $initFormatter = null): \NumberFormatter
    {
        /** @var array<\NumberFormatter> $formatters */
        static $formatters = [];
        $locale ??= App::getLocale();
        $formatter = $formatters[$locale] ??= new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        if ($initFormatter) {
            $initFormatter($formatter);
        }
        return $formatter;
    }
}
