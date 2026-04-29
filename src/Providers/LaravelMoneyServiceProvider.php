<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Providers;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Formatters\MoneyFormatter;

class LaravelMoneyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/money.php', 'money');

        $this->app->singletonIf(Contracts\MoneyFormatter::class, function () {
            return new MoneyFormatter();
        });

        $this->app->singletonIf(Contracts\MoneyDataProvider::class, function (Application $app) {
            return new MoneyDataProvider($app->make(Config::class));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/money.php' => App::configPath('money.php'),
        ], 'money-config');
    }
}
