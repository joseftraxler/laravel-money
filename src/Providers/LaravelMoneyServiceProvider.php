<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Providers;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Formatters\MoneyFormatter;

class LaravelMoneyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singletonIf(Contracts\MoneyFormatter::class, function () {
            return new MoneyFormatter();
        });

        $this->app->singletonIf(Contracts\MoneyDataProvider::class, function (Application $app) {
            return new MoneyDataProvider($app->make(Config::class));
        });
    }
}
