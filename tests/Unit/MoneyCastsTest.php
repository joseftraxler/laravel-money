<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;
use Tests\Examples\CurrencyEnum;
use Tests\Examples\ModelExample;

it('casts money:EUR to model attribute', function () {
    $model = new ModelExample([
        'price_eur' => '1.00',
    ]);

    expect($model->price_eur)->toBeInstanceOf(Money::class);
    expect($model->price_eur->getDecimal())->toBe('1.00');
    expect($model->price_eur->getCurrency())->toBe(Currency::of('EUR'));
});

it('casts money:currency to model attribute', function () {
    $model = new ModelExample([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    expect($model->paid)->toBeInstanceOf(Money::class);
    expect($model->paid->getDecimal())->toBe('1.00');
    expect($model->paid->getCurrency())->toBe(Currency::of('USD'));
});

it('sets money attribute', function () {
    $model = new ModelExample([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = new Money(2, Currency::of('EUR'));

    expect($model->price_eur->getDecimal())->toBe('2.00');
});

it('sets currency attribute when change money', function () {
    $model = new ModelExample([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    $model->paid = new Money(2, Currency::of('EUR'));

    expect($model->paid->getCurrency()->getCode())->toBe('EUR');
});

it('casts null money with fixed currency', function () {
    $model = new ModelExample([
        'price_eur' => null,
    ]);

    expect($model->price_eur)->toBeNull();
});

it('casts null money with dynamic currency', function () {
    $model = new ModelExample([
        'paid' => null,
        'paid_currency' => 'USD',
    ]);

    expect($model->paid)->toBeNull();
});

it('casts money in cents with fixed currency')
    ->expect((new ModelExample([
        'paid_in_cents' => 150,
        'paid_in_cents_currency' => CurrencyEnum::USD,
    ]))->paid_in_cents)
    ->toBeInstanceOf(Money::class)
    ->getDecimal()->toBe('1.50')
    ->getCents()->toBe('150')
    ->getCurrency()->toBe(Currency::of(CurrencyEnum::USD))
;

it('sets money in cents attribute', function () {
    $model = new ModelExample([
        'paid_in_cents' => 100,
        'paid_in_cents_currency' => 'USD',
    ]);

    $model->paid_in_cents = Money::fromCents('250', Currency::of('EUR'));

    expect($model->paid_in_cents->getCents())->toBe('250');
});

it('sets null money attribute', function () {
    $model = new ModelExample([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = null;

    expect($model->price_eur)->toBeNull();
});

it('stores decimal value in database attribute', function () {
    $model = new ModelExample([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = new Money('123.45', Currency::of('EUR'));

    expect($model->getRawAttribute('price_eur'))->toBe('123.45');
});

it('stores currency code in separate column', function () {
    $model = new ModelExample([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    $model->paid = new Money(3, Currency::of('GBP'));

    expect($model->getAttribute('paid_currency'))->toBe('GBP');
});

it('returns null when currency column is null', function () {
    $model = new ModelExample([
        'paid' => '1.00',
        'paid_currency' => null,
    ]);

    expect($model->paid)->toBeNull();
});
