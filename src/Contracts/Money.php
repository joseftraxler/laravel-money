<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface Money
{
    public function __construct(int|string $amount, Currency|string $currency);

    public function getAmount(): string;

    public function getCurrency(): Currency;
}
