<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyFormatter
{
    public function humanReadableAmount(Money $money): string;

    public function humanReadableCurrency(Currency|Money $currencyOrMoney): string;

    public function humanReadable(Money $money): string;
}
