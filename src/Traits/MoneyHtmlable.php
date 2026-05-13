<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Traits;

use JosefTraxler\LaravelMoney\Facades\MoneyFormatter;

/**
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\Money
 * @phpstan-require-implements \Illuminate\Contracts\Support\Htmlable
 */
trait MoneyHtmlable
{
    public function toHtml(): string
    {
        return $this->toHumanReadable();
    }

    public function toHumanReadable(?string $locale = null): string
    {
        return MoneyFormatter::humanReadable($this, $locale);
    }

    public function toHumanReadableCurrency(?string $locale = null): string
    {
        return MoneyFormatter::humanReadableCurrency($this, $locale);
    }

    public function toHumanReadableAmount(?string $locale = null): string
    {
        return MoneyFormatter::humanReadableAmount($this, $locale);
    }
}
