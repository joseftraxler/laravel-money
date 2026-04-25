<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface Money
{
    /**
     * @param int|numeric-string $amount
     * @param Currency|non-empty-string $currency
     */
    public function __construct(int|string $amount, Currency|string $currency);

    /**
     * @return numeric-string
     */
    public function getAmount(): string;

    public function getCurrency(): Currency;
}
