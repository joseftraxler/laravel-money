<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface Currencyable
{
    /** @return non-empty-string */
    public function getCode(): string;
}
