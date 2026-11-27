<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Support\Assert;

it('passes for a non-empty array', function () {
    expect(fn () => Assert::notEmpty([0], 'empty'))->not->toThrow(\InvalidArgumentException::class);
});

it('throws for an empty array', function () {
    expect(fn () => Assert::notEmpty([], 'empty'))->toThrow(\InvalidArgumentException::class, 'empty');
});

it('passes for numeric values', function (mixed $value) {
    expect(fn () => Assert::numeric($value, 'not numeric'))->not->toThrow(\InvalidArgumentException::class);
})->with([0, -5, 1.5, '1', '-0.5', '+2.25', '12345678901234567890.123456789']);

it('throws for non-numeric values', function (mixed $value) {
    expect(fn () => Assert::numeric($value, 'not numeric'))->toThrow(\InvalidArgumentException::class, 'not numeric');
})->with([INF, -INF, NAN, '', 'abc', '1e3', ' 1', '1.', '.5', null, true, [[1]]]);

it('passes for non-negative values', function (int|float|string $value) {
    expect(fn () => Assert::nonNegative($value, 'negative'))->not->toThrow(\InvalidArgumentException::class);
})->with([0, 0.0, '0', '-0', '-0.00', 1, 0.5, '+0.000000000000000000001']);

it('throws for negative values', function (int|float|string $value) {
    expect(fn () => Assert::nonNegative($value, 'negative'))->toThrow(\InvalidArgumentException::class, 'negative');
})->with([-1, -0.5, '-1', '-0.000000000000000000001']);

it('passes for positive values', function (int|float|string $value) {
    expect(fn () => Assert::positive($value, 'not positive'))->not->toThrow(\InvalidArgumentException::class);
})->with([1, 0.5, '1', '+1', '0.000000000000000000001']);

it('throws for zero or negative values', function (int|float|string $value) {
    expect(fn () => Assert::positive($value, 'not positive'))->toThrow(\InvalidArgumentException::class, 'not positive');
})->with([0, 0.0, -1, '0', '-0', '0.00', '-0.000000000000000000001']);
