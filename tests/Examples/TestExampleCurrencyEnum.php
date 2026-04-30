<?php

declare(strict_types=1);

namespace Tests\Examples;

enum TestExampleCurrencyEnum: string implements \JosefTraxler\LaravelMoney\Contracts\Currencyable
{
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';
    case CZK = 'CZK';
    case JPY = 'JPY';
    case KRW = 'KRW';

    public function getCode(): string
    {
        return $this->value;
    }
}
