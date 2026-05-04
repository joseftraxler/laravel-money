<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;
use Tests\Examples\TestExampleCurrencyEnum;
use Tests\Examples\TestExampleModel;

it('casts money:EUR to model attribute', function () {
    $model = new TestExampleModel([
        'price_eur' => '1.00',
    ]);

    expect($model->price_eur)->toBeInstanceOf(Money::class);
    expect($model->price_eur->getDecimal())->toBe('1.00');
    expect($model->price_eur->getCurrency())->toBe(Currency::of('EUR'));
});

it('casts money:currency to model attribute', function () {
    $model = new TestExampleModel([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    expect($model->paid)->toBeInstanceOf(Money::class);
    expect($model->paid->getDecimal())->toBe('1.00');
    expect($model->paid->getCurrency())->toBe(Currency::of('USD'));
});

it('sets money attribute', function () {
    $model = new TestExampleModel([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = new Money(2, Currency::of('EUR'));

    expect($model->price_eur->getDecimal())->toBe('2.00');
});

it('sets currency attribute when change money', function () {
    $model = new TestExampleModel([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    $model->paid = new Money(2, Currency::of('EUR'));

    expect($model->paid->getCurrency()->getCode())->toBe('EUR');
});

it('casts null money with fixed currency', function () {
    $model = new TestExampleModel([
        'price_eur' => null,
    ]);

    expect($model->price_eur)->toBeNull();
});

it('casts null money with dynamic currency', function () {
    $model = new TestExampleModel([
        'paid' => null,
        'paid_currency' => 'USD',
    ]);

    expect($model->paid)->toBeNull();
});

it('casts money in cents with fixed currency')
    ->expect((new TestExampleModel([
        'paid_in_cents' => 150,
        'paid_in_cents_currency' => TestExampleCurrencyEnum::USD,
    ]))->paid_in_cents)
    ->toBeInstanceOf(Money::class)
    ->getDecimal()->toBe('1.50')
    ->getCents()->toBe('150')
    ->getCurrency()->toBe(Currency::of(TestExampleCurrencyEnum::USD))
;

it('stores cents value in database attribute', function () {
    $model = new TestExampleModel([
        'paid_in_cents' => 100,
        'paid_in_cents_currency' => 'USD',
    ]);

    $model->paid_in_cents = Money::fromCents('250', Currency::of('EUR'));

    expect($model->getRawAttribute('paid_in_cents'))->toBe('250');
    expect($model->getAttribute('paid_in_cents_currency'))->toBe(TestExampleCurrencyEnum::EUR);
});

it('sets null money attribute', function () {
    $model = new TestExampleModel([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = null;

    expect($model->price_eur)->toBeNull();
});

it('stores decimal value in database attribute', function () {
    $model = new TestExampleModel([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = new Money('123.45', Currency::of('EUR'));

    expect($model->getRawAttribute('price_eur'))->toBe('123.45');
});

it('stores currency code in separate column', function () {
    $model = new TestExampleModel([
        'paid' => '1.00',
        'paid_currency' => 'USD',
    ]);

    $model->paid = new Money(3, Currency::of('GBP'));

    expect($model->getAttribute('paid_currency'))->toBe('GBP');
});

it('returns null when currency column is null', function () {
    $model = new TestExampleModel([
        'paid' => '1.00',
        'paid_currency' => null,
    ]);

    expect($model->paid)->toBeNull();
});

it('stores cents cast as cents, not decimal', function () {
    $model = new TestExampleModel([
        'paid_in_cents' => 100,
        'paid_in_cents_currency' => 'USD',
    ]);

    $model->paid_in_cents = Money::fromCents('299', Currency::of('EUR'));

    expect($model->getRawAttribute('paid_in_cents'))->toBe('299');
    expect($model->paid_in_cents->getCents())->toBe('299');
    expect($model->paid_in_cents->getDecimal())->toBe('2.99');
});

it('rejects invalid currency codes', function () {
    (new TestExampleModel([
        'price_eur' => '10.00',
        'paid' => '5.00',
        'paid_currency' => 'INVALID',
    ]))->paid;
})->throws(\InvalidArgumentException::class, 'Currency code must be a valid ISO 4217 code.');

it('formats different currency precisions without leaking formatter state', function () {
    $modelJpy = new TestExampleModel([
        'paid' => '1000',
        'paid_currency' => TestExampleCurrencyEnum::JPY->value,
    ]);

    $modelEur = new TestExampleModel([
        'price_eur' => '10.50',
    ]);

    expect($modelJpy->paid->getCurrency()->getPrecision())->toBe(0);
    expect($modelJpy->paid->getDecimal())->toBe('1000');

    expect($modelEur->price_eur->getCurrency()->getPrecision())->toBe(2);
    expect($modelEur->price_eur->getDecimal())->toBe('10.50');

    // Verify JPY still has correct precision after EUR
    expect($modelJpy->paid->getCurrency()->getPrecision())->toBe(0);
});

it('uses resolved app locale in currency symbol cache key', function () {
    $model1 = new TestExampleModel([
        'price_eur' => '10.00',
    ]);

    $model2 = new TestExampleModel([
        'paid' => '20.00',
        'paid_currency' => 'EUR',
    ]);

    expect($model1->price_eur->getCurrency())->toBe($model2->paid->getCurrency());
    expect($model1->price_eur->getCurrency()->getCode())->toBe('EUR');
});

it('accepts integer decimal values through fromDecimal', function () {
    $model = new TestExampleModel([
        'price_eur' => '1.00',
    ]);

    $model->price_eur = Money::fromDecimal(50, Currency::of('EUR'));

    expect($model->price_eur->getDecimal())->toBe('50.00');
    expect($model->price_eur->getCents())->toBe('5000');
});

it('detects zero without float conversion', function () {
    $model1 = new TestExampleModel([
        'price_eur' => '0.00',
    ]);

    $model2 = new TestExampleModel([
        'price_eur' => 0,
    ]);

    expect($model1->price_eur->isZero())->toBeTrue();
    expect($model1->price_eur->getCents())->toBe('0');

    expect($model2->price_eur->isZero())->toBeTrue();
    expect($model2->price_eur->getCents())->toBe('0');
});
