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
    public function toHtml()
    {
        return MoneyFormatter::humanReadable($this);
    }
}
