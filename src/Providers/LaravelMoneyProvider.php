<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Providers;

use Illuminate\Support\ServiceProvider;
use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Formatters\MoneyFormatter;

class LaravelMoneyProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindIf(Contracts\Currency::class, function (string $code) {
            return Currency::of($code);
        });

        $this->app->bindIf(Contracts\Money::class, function (string $cents, string|Contracts\Currency $currency) {
            return new Money($cents, $currency instanceof Contracts\Currency ? $currency : $this->app->make(Contracts\Currency::class, ['code' => $currency]));
        });

        $this->app->singletonIf(Contracts\MoneyFormatter::class, function () {
            return new MoneyFormatter();
        });
    }
}
