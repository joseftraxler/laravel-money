<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * MoneyFormatter provides a facade for accessing the MoneyFormatter contract
 * within the LaravelMoney package.
 *
 * @method static string humanReadable(\JosefTraxler\LaravelMoney\Contracts\Money $money, ?string $locale = null)
 * @method static string humanReadableCurrency(\JosefTraxler\LaravelMoney\Contracts\Currency|\JosefTraxler\LaravelMoney\Contracts\Money $currencyOrMoney, ?string $locale = null)
 * @method static string humanReadableAmount(\JosefTraxler\LaravelMoney\Contracts\Money $money, ?string $locale = null)
 *
 * @see \JosefTraxler\LaravelMoney\Contracts\MoneyFormatter
 */
class MoneyFormatter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \JosefTraxler\LaravelMoney\Contracts\MoneyFormatter::class;
    }

}
