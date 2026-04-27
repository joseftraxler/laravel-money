<?php

declare(strict_types=1);

$app = new \Tests\Stubs\FakeApplication();
\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

$provider = new \JosefTraxler\LaravelMoney\Providers\LaravelMoneyServiceProvider($app);

$provider->register();

pest()
    ->extend(Tests\TestCase::class)
    ->in('Unit');
