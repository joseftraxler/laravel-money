<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyDataProvider
{
    public function defaultCurrency(): ?Currencyable;

    /**
     * @param non-empty-string $currencyCode
     *
     * @return non-negative-int
     */
    public function precision(string $currencyCode): int;
}
