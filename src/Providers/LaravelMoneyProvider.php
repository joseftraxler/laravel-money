<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Providers;

use Illuminate\Support\ServiceProvider;
use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Formatters\MoneyFormatter;
use JosefTraxler\LaravelMoney\Money;

class LaravelMoneyProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindIf(Contracts\Currency::class, function (string $code) {
            /** @var non-empty-string $code */
            return Currency::of($code);
        });

        $this->app->bindIf(Contracts\Money::class, function (string $amount, string|Contracts\Currency $currency) {
            /** @var numeric-string $amount */
            return new Money($amount, $currency instanceof Contracts\Currency ? $currency : $this->app->make(Contracts\Currency::class, ['code' => $currency]));
        });

        $this->app->singletonIf(Contracts\MoneyFormatter::class, function () {
            return new MoneyFormatter();
        });
    }
}
