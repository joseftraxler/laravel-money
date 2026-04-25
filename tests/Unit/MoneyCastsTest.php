<?php

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

it('casts money:EUR to model attribute', function () {
    $model = new \Tests\Examples\ModelExample([
        'price_eur' => '1.00',
    ]);

    expect($model->price_eur)->toBeInstanceOf(Money::class);
    expect($model->price_eur->getAmount())->toBe('100');
    expect($model->price_eur->getCurrency())->toBe(Currency::of('EUR'));
});

it('casts money:currency to model attribute', function () {
    $model = new \Tests\Examples\ModelExample([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    expect($model->paid)->toBeInstanceOf(Money::class);
    expect($model->paid->getAmount())->toBe('100');
    expect($model->paid->getCurrency())->toBe(Currency::of('USD'));
});

it('sets money attribute', function () {
    $model = new \Tests\Examples\ModelExample([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = new Money(200, Currency::of('EUR'));

    expect($model->price_eur->getAmount())->toBe('200');
});

it('sets currency attribute when change money', function () {
    $model = new \Tests\Examples\ModelExample([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    $model->paid = new Money(200, Currency::of('EUR'));

    expect($model->paid->getCurrency()->getCode())->toBe('EUR');
});

it('casts null money with fixed currency', function () {
    $model = new \Tests\Examples\ModelExample([
        'price_eur' => null,
    ]);

    expect($model->price_eur)->toBeNull();
});

it('casts null money with dynamic currency', function () {
    $model = new \Tests\Examples\ModelExample([
        'paid' => null,
        'paid_currency' => 'USD',
    ]);

    expect($model->paid)->toBeNull();
});

it('casts money in cents with fixed currency', function () {
    $model = new \Tests\Examples\ModelExample([
        'paid_in_cents' => 150,
        'paid_currency' => 'USD',
    ]);

    expect($model->paid_in_cents)->toBeInstanceOf(Money::class);
    expect($model->paid_in_cents->getAmount())->toBe('150');
    expect($model->paid_in_cents->getCurrency())->toBe(Currency::of('USD'));
});

it('sets money in cents attribute', function () {
    $model = new \Tests\Examples\ModelExample([
        'paid_in_cents' => 100,
        'paid_currency' => 'USD',
    ]);

    $model->paid_in_cents = new Money(250, Currency::of('EUR'));

    expect($model->paid_in_cents->getAmount())->toBe('250');
});

it('sets null money attribute', function () {
    $model = new \Tests\Examples\ModelExample([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = null;

    expect($model->price_eur)->toBeNull();
});

it('stores decimal value in database attribute', function () {
    $model = new \Tests\Examples\ModelExample([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = new Money(12345, Currency::of('EUR'));

    expect($model->getRawAttribute('price_eur'))->toBe('123.45');
});

it('stores currency code in separate column', function () {
    $model = new \Tests\Examples\ModelExample([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    $model->paid = new Money(300, Currency::of('GBP'));

    expect($model->getAttribute('paid_currency'))->toBe('GBP');
});

it('returns null when currency column is null', function () {
    $model = new \Tests\Examples\ModelExample([
        'paid' => '1.00',
        'paid_currency' => null,
    ]);

    expect($model->paid)->toBeNull();
});

