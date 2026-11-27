<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

use JosefTraxler\LaravelMoney\Enums\RoundMode;
use JosefTraxler\LaravelMoney\Enums\RoundUnit;

interface MoneyCalculator
{
    public function add(Money ...$money): Money;

    public function subtract(Money ...$money): Money;

    /**
     * @param int|numeric-string $multiplier
     */
    public function multiply(int|string $multiplier): Money;

    /**
     * @param int|numeric-string $divisor
     */
    public function divide(int|string $divisor): Money;

    /**
     * Splits the amount by the given ratios without losing any minor units.
     *
     * @template TKey of array-key
     * @param non-empty-array<TKey, int<0, max>|float|numeric-string> $ratios
     * @return non-empty-array<TKey, Money>
     */
    public function allocate(array $ratios): array;

    /**
     * Splits the amount into n equal parts without losing any minor units.
     *
     * @param positive-int $n
     * @return non-empty-list<Money>
     */
    public function allocateTo(int $n): array;

    public function isNegative(): bool;

    public function isPositive(): bool;

    public function isZero(): bool;

    public function equals(Money $money): bool;

    public function greaterThan(Money $money): bool;

    public function greaterThanOrEqual(Money $money): bool;

    public function lessThan(Money $money): bool;

    public function lessThanOrEqual(Money $money): bool;

    public function compare(Money $money): int;

    public function round(
        RoundMode $mode = RoundMode::HalfUp,
        RoundUnit $unit = RoundUnit::MajorUnit
    ): Money;
}
