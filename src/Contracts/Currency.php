<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface Currency extends Currencyable, \Stringable
{
    public function equals(Currencyable|string $currency): bool;

    /** @return non-negative-int */
    public function getPrecision(): int;
}
