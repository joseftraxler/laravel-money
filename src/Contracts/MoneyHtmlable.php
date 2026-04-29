<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

use Illuminate\Contracts\Support\Htmlable;

interface MoneyHtmlable extends Htmlable
{
    public function toHumanReadable(?string $locale = null): string;

    public function toHumanReadableCurrency(?string $locale = null): string;

    public function toHumanReadableAmount(?string $locale = null): string;
}
