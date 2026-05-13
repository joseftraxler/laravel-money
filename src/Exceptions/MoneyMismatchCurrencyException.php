<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Exceptions;

class MoneyMismatchCurrencyException extends \InvalidArgumentException implements MoneyException
{
}
