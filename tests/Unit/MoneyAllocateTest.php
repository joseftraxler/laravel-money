<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Contracts\Money as MoneyContract;
use JosefTraxler\LaravelMoney\Money;

/**
 * @param array<array-key, MoneyContract> $parts
 * @return array<array-key, string>
 */
function allocatedDecimals(array $parts): array
{
    return array_map(fn (MoneyContract $part): string => $part->getDecimal(), $parts);
}

/**
 * @param array<array-key, MoneyContract> $parts
 */
function allocatedSum(MoneyContract $original, array $parts): string
{
    return Money::fromCents('0', $original->getCurrency())->add(...array_values($parts))->getDecimal();
}

it('allocates money by ratios', function () {
    expect(allocatedDecimals(Money::USD('0.10')->allocate([1, 2])))->toBe(['0.03', '0.07'])
        ->and(allocatedDecimals(Money::USD('0.05')->allocate([3, 7])))->toBe(['0.02', '0.03'])
        ->and(allocatedDecimals(Money::USD('100')->allocate([70, 30])))->toBe(['70.00', '30.00'])
        ->and(allocatedDecimals(Money::USD('1')->allocate([0.5, 0.25, 0.25])))->toBe(['0.50', '0.25', '0.25']);
});

it('allocates money into equal parts', function () {
    expect(allocatedDecimals(Money::USD('0.05')->allocateTo(3)))->toBe(['0.02', '0.02', '0.01'])
        ->and(allocatedDecimals(Money::USD('10')->allocateTo(4)))->toBe(['2.50', '2.50', '2.50', '2.50'])
        ->and(allocatedDecimals(Money::USD('0.05')->allocateTo(1)))->toBe(['0.05'])
        ->and(allocatedDecimals(Money::JPY('100')->allocateTo(3)))->toBe(['34', '33', '33']);
});

it('allocates negative amounts symmetrically to positive ones', function () {
    expect(allocatedDecimals(Money::USD('-0.05')->allocateTo(3)))->toBe(['-0.02', '-0.02', '-0.01'])
        ->and(allocatedDecimals(Money::USD('-0.10')->allocate([1, 2])))->toBe(['-0.03', '-0.07']);
});

it('allocates zero amount into zero parts', function () {
    expect(allocatedDecimals(Money::USD('0')->allocateTo(3)))->toBe(['0.00', '0.00', '0.00'])
        ->and(allocatedDecimals(Money::USD('0')->allocate([1, 2])))->toBe(['0.00', '0.00']);
});

it('allocates parts that add up exactly to the original amount', function (string $amount, array $ratios) {
    $money = Money::USD($amount);

    expect(allocatedSum($money, $money->allocate($ratios)))->toBe($money->getDecimal())
        ->and(allocatedSum($money, $money->allocateTo(count($ratios))))->toBe($money->getDecimal());
})->with([
    ['0.05', [1, 1, 1]],
    ['0.01', [1, 1, 1, 1]],
    ['100.00', [1, 1, 1]],
    ['-0.05', [1, 1, 1]],
    ['-100.01', [3, 5, 7]],
    ['999.99', [0.1, 0.2, 0.7]],
    ['0', [1, 2, 3]],
    ['12345678901234567890.99', [1, 3, 9, 27]],
]);

it('keeps the currency of the original amount', function () {
    $parts = [...Money::EUR('10')->allocate([1, 2]), ...Money::EUR('10')->allocateTo(3)];

    expect($parts)->each->toBeInstanceOf(Money::class)
        ->and(array_map(fn (MoneyContract $part): string => $part->getCurrency()->getCode(), $parts))
        ->each->toBe('EUR');
});

it('keeps the keys of the ratios', function () {
    $parts = Money::USD('0.10')->allocate(['alice' => 1, 'bob' => 2]);

    expect(allocatedDecimals($parts))->toBe(['alice' => '0.03', 'bob' => '0.07'])
        ->and(allocatedDecimals(Money::USD('0.10')->allocate([5 => 1, 2 => 1])))->toBe([5 => '0.05', 2 => '0.05']);
});

it('gives a zero part for a zero ratio', function () {
    expect(allocatedDecimals(Money::USD('0.05')->allocate([1, 0, 1])))->toBe(['0.03', '0.00', '0.02'])
        ->and(allocatedDecimals(Money::USD('-0.05')->allocate([0, 1])))->toBe(['0.00', '-0.05']);
});

it('throws exception for invalid ratios', function (array $ratios) {
    expect(fn () => Money::USD('10')->allocate($ratios))->toThrow(\InvalidArgumentException::class);
})->with([
    'empty ratios' => [[]],
    'negative ratio' => [[1, -1, 2]],
    'zero sum of ratios' => [[0, 0]],
]);

it('throws exception for invalid number of parts', function (int $n) {
    expect(fn () => Money::USD('10')->allocateTo($n))->toThrow(\InvalidArgumentException::class);
})->with([0, -1]);
