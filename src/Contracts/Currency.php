<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface Currency extends \Stringable
{
    public function getCode(): string;

    public function equals(Currency $currency): bool;
}
