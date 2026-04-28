<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyFormatter
{
    public function humanReadableAmount(Money $money, ?string $locale = null): string;

    public function humanReadableCurrency(Currency|Money $currencyOrMoney, ?string $locale = null): string;

    public function humanReadable(Money $money, ?string $locale = null): string;
}
