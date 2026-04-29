<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyDataProvider
{
    public function defaultCurrency(): ?Currency;

    /**
     * @return non-negative-int
     */
    public function precision(Currency $currency): int;
}
