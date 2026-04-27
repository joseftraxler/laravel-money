<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Traits;

use JosefTraxler\LaravelMoney\Contracts\Money;
use JosefTraxler\LaravelMoney\Enums\RoundMode;
use JosefTraxler\LaravelMoney\Enums\RoundUnit;

/**
 * @phpstan-require-implements Money
 */
trait MoneyCalculator
{
    public function add(Money ...$money): Money
    {
        return static::fromCents(
            self::toMoneyPhp($this)->add(...array_map(self::toMoneyPhp(...), $money))->getAmount(),
            $this->getCurrency(),
        );
    }

    public function subtract(Money ...$money): Money
    {
        return static::fromCents(
            self::toMoneyPhp($this)->subtract(...array_map(self::toMoneyPhp(...), $money))->getAmount(),
            $this->getCurrency(),
        );
    }

    /**
     * @param int|numeric-string $multiplier
     */
    public function multiply(int|string $multiplier): Money
    {
        return static::fromCents(
            self::toMoneyPhp($this)->multiply($multiplier)->getAmount(),
            $this->getCurrency(),
        );
    }

    /**
     * @param int|numeric-string $divisor
     */
    public function divide(int|string $divisor): Money
    {
        if ((float) $divisor === 0.0) {
            throw new \InvalidArgumentException('Division by zero is not allowed');
        }
        return static::fromCents(
            self::toMoneyPhp($this)->divide($divisor)->getAmount(),
            $this->getCurrency(),
        );
    }

    public function isNegative(): bool
    {
        return $this->getDecimal()[0] === '-';
    }

    public function isPositive(): bool
    {
        return ! $this->isNegative() && ! $this->isZero();
    }

    public function isZero(): bool
    {
        return (float) $this->getDecimal() === 0.0;
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

    public function round(RoundMode $mode = RoundMode::HalfUp, RoundUnit $unit = RoundUnit::MajorUnit): Money
    {
        return static::fromCents(
            self::toMoneyPhp($this)->roundToUnit(
                $unit->toCentsUnit($this->getCurrency()),
                $mode->value,
            )->getAmount(),
            $this->getCurrency(),
        );
    }

    private static function toMoneyPhp(Money $money): \Money\Money
    {
        return new \Money\Money(
            $money->getCents(),
            new \Money\Currency($money->getCurrency()->getCode()),
        );
    }
}
