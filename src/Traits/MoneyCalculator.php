<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Traits;

use JosefTraxler\LaravelMoney\Contracts\Money;

/**
 * @phpstan-require-implements Money
 */
trait MoneyCalculator
{
    public function add(Money ...$money): Money
    {
        return new static(
            self::toMoneyPhp($this)->add(...array_map(self::toMoneyPhp(...), $money))->getAmount(),
            $this->getCurrency(),
        );
    }

    public function subtract(Money ...$money): Money
    {
        return new static(
            self::toMoneyPhp($this)->subtract(...array_map(self::toMoneyPhp(...), $money))->getAmount(),
            $this->getCurrency(),
        );
    }

    public function multiply(int|string $multiplier): Money
    {
        return new static(
            self::toMoneyPhp($this)->multiply($multiplier)->getAmount(),
            $this->getCurrency(),
        );
    }

    public function divide(int|string $divisor): Money
    {
        if ( ! is_numeric($divisor) || (float) $divisor === 0.0) {
            throw new \InvalidArgumentException('Division by zero is not allowed');
        }
        return new static(
            self::toMoneyPhp($this)->divide($divisor)->getAmount(),
            $this->getCurrency(),
        );
    }

    public function isNegative(): bool
    {
        return self::toMoneyPhp($this)->isNegative();
    }

    public function isPositive(): bool
    {
        return self::toMoneyPhp($this)->isPositive();
    }

    public function isZero(): bool
    {
        return self::toMoneyPhp($this)->isZero();
    }

    public function equals(Money $money): bool
    {
        return self::toMoneyPhp($this)->equals(self::toMoneyPhp($money));
    }

    public function greaterThan(Money $money): bool
    {
        return self::toMoneyPhp($this)->greaterThan(self::toMoneyPhp($money));
    }

    public function greaterThanOrEqual(Money $money): bool
    {
        return self::toMoneyPhp($this)->greaterThanOrEqual(self::toMoneyPhp($money));
    }

    public function lessThan(Money $money): bool
    {
        return self::toMoneyPhp($this)->lessThan(self::toMoneyPhp($money));
    }

    public function lessThanOrEqual(Money $money): bool
    {
        return self::toMoneyPhp($this)->lessThanOrEqual(self::toMoneyPhp($money));
    }

    public function compare(Money $money): int
    {
        return self::toMoneyPhp($this)->compare(self::toMoneyPhp($money));
    }

    private static function toMoneyPhp(Money $money): \Money\Money
    {
        return new \Money\Money(
            $money->getAmount(),
            new \Money\Currency($money->getCurrency()->getCode()),
        );
    }
}
