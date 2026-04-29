<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Facades;

/**
 * A facade for accessing the MoneyDatasetProvider, providing a static interface
 * for working with monetary dataset configurations and defaults.
 *
 * @method static \JosefTraxler\LaravelMoney\Contracts\Currency|null defaultCurrency()
 * @method static non-negative-int precision(\JosefTraxler\LaravelMoney\Contracts\Currency $currencyCode)
 * @method static class-string<\JosefTraxler\LaravelMoney\Contracts\Currency> getCurrencyClassName()
 *
 * @see \JosefTraxler\LaravelMoney\Contracts\MoneyDataProvider
 */
class MoneyDataProvider extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \JosefTraxler\LaravelMoney\Contracts\MoneyDataProvider::class;
    }
}
