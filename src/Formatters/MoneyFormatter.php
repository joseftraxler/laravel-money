<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Formatters;

use Illuminate\Support\Facades\App;
use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\Contracts\Currencyable;
use JosefTraxler\LaravelMoney\Contracts\Money;
use NumberFormatter;

class MoneyFormatter implements \JosefTraxler\LaravelMoney\Contracts\MoneyFormatter
{
    public function humanReadableAmount(Money $money, ?string $locale = null): string
    {
        return self::decimalFormatter(
            $locale,
            $money->getCurrency(),
        )->format(
            (float) $money->getDecimal(),
        ) ?: $money->getDecimal();
    }

    public function humanReadableCurrency(Currencyable|Money $currencyOrMoney, ?string $locale = null): string
    {
        /** @var array<string, string> $cache */
        static $cache = [];

        $locale ??= App::getLocale();

        $currency = $currencyOrMoney instanceof Money
            ? $currencyOrMoney->getCurrency()
            : $currencyOrMoney;

        return $cache[$locale . '-' . $currency->getCode()] ??= self::currencyFormatter(
            $locale,
            $currency,
        )->getSymbol(NumberFormatter::CURRENCY_SYMBOL) ?: $currency->getCode();
    }

    public function humanReadable(Money $money, ?string $locale = null): string
    {
        return (string) self::currencyFormatter(
            $locale,
            $money->getCurrency(),
        )->formatCurrency(
            (float) $money->getDecimal(),
            $money->getCurrency()->getCode(),
        ) ?: $money->getCurrency()->getCode() . "\u{00A0}" . $money->getDecimal();
    }

    protected static function currencyFormatter(?string $locale, Currency $currency): \NumberFormatter
    {
        /** @var array<\NumberFormatter> $formatters */
        static $formatters = [];
        $locale ??= App::getLocale();
        $precision = $currency->getPrecision();

        return $formatters[$locale . ':' . $currency->getCode() . ':' . $precision] ??= tap(
            new \NumberFormatter($locale, \NumberFormatter::CURRENCY),
            function (\NumberFormatter $formatter) use ($currency, $precision) {
                $formatter->setTextAttribute(
                    \NumberFormatter::CURRENCY_CODE,
                    $currency->getCode(),
                );
                $formatter->setAttribute(
                    \NumberFormatter::FRACTION_DIGITS,
                    $precision,
                );
            }
        );
    }

    protected static function decimalFormatter(?string $locale, Currency $currency): \NumberFormatter
    {
        /** @var array<\NumberFormatter> $formatters */
        static $formatters = [];
        $locale ??= App::getLocale();
        $precision = $currency->getPrecision();

        return $formatters[$locale . ':' . $precision] ??= tap(
            new \NumberFormatter($locale, \NumberFormatter::DECIMAL),
            fn (\NumberFormatter $formatter) => $formatter->setAttribute(
                \NumberFormatter::FRACTION_DIGITS,
                $precision,
            ),
        );
    }
}
