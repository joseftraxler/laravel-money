<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyDataProvider
{
    public function defaultCurrency(): ?Currencyable;

    /** @return non-negative-int */
    public function precision(string $currencyCode): int;

    /** @return class-string<Currencyable> */
    public function getCurrencyClassName(): string;
}
