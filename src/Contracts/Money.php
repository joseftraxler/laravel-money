<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface Money
{
    /**
     * @param int|numeric-string $decimal
     * @param Currency|non-empty-string $currency
     */
    public function __construct(int|string $decimal, Currency|string $currency);

    /** @return numeric-string */
    public function getDecimal(): string;

    /** @return numeric-string */
    public function getCents(): string;

    public function getCurrency(): Currency;
}
