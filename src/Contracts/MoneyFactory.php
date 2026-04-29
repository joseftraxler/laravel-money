<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyFactory
{
    /**
     * @param int|float|numeric-string $decimal
     * @param non-empty-string|Currencyable $currency
     *
     * @return static&Money
     */
    public static function fromDecimal(int|float|string $decimal, string|Currencyable $currency): Money;

    /**
     * @param int|numeric-string $cents
     * @param non-empty-string|Currencyable $currency
     *
     * @return static&Money
     */
    public static function fromCents(int|string $cents, string|Currencyable $currency): Money;
}
