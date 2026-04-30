<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyGettable
{
    /** @return numeric-string */
    public function getDecimal(): string;

    /** @return numeric-string */
    public function getCents(): string;

    public function getCurrency(): Currency;
}
