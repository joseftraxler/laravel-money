<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Exceptions;

use JosefTraxler\LaravelMoney\Contracts\Currencyable;

class MoneyMismatchCurrencyException extends \InvalidArgumentException implements MoneyException
{
    public static function make(string|Currencyable $expected, string|Currencyable $actual): self
    {
        return new self(
            sprintf(
                'Money must be currency of "%s", "%s" given.',
                ($expected instanceof Currencyable ? $expected->getCode() : $expected),
                ($actual instanceof Currencyable ? $actual->getCode() : $actual),
            ),
        );
    }
}
