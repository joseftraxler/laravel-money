<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Providers;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Formatters\MoneyFormatter;
use JosefTraxler\LaravelMoney\Money;

class LaravelMoneyProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindIf(Contracts\Currency::class, function (Application $app, array $parameters) {
            /** @var non-empty-string $code */
            $code = $parameters['code']
                ?? $app->make(Config::class)->get('money.default_currency')
                ?? throw new \InvalidArgumentException('No currency code provided and no default currency configured.');

            return Currency::of($code);
        });

        $this->app->bindIf(Contracts\Money::class, function (Application $app, array $parameters) {
            /** @var numeric-string $amount */
            $amount = $parameters['amount']
                ?? throw new \InvalidArgumentException('No amount provided.');

            $currency = $parameters['currency']
                ?? $app->make(Config::class)->get('money.default_currency')
                ?? throw new \InvalidArgumentException('No currency provided and no default currency configured.');

            if (! $currency instanceof Contracts\Currency) {
                $currency = $app->make(Contracts\Currency::class, ['code' => $currency]);
            }

            return new Money($amount, $currency);
        });

        $this->app->singletonIf(Contracts\MoneyFormatter::class, function () {
            return new MoneyFormatter();
        });
    }
}
