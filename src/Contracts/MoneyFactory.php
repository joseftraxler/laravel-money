<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

use JosefTraxler\LaravelMoney\Currency;

interface MoneyFactory
{
    /**
     * @param float|numeric-string $decimal
     * @param non-empty-string|Currency $currency
     */
    public static function fromDecimal(float|string $decimal, string|Currency $currency): Money;

    /**
     * @param int|numeric-string $cents
     * @param non-empty-string|Currency $currency
     * @return Money
     */
    public static function fromCents(int|string $cents, string|Currency $currency): Money;
}
