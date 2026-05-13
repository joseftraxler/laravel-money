<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Traits;

use JosefTraxler\LaravelMoney\Contracts\Money;
use JosefTraxler\LaravelMoney\Enums\RoundMode;
use JosefTraxler\LaravelMoney\Enums\RoundUnit;
use JosefTraxler\LaravelMoney\Exceptions\MoneyMismatchCurrencyException;

/**
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyGettable
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyCalculator
 */
trait MoneyCalculator
{
    /**
     * @throws MoneyMismatchCurrencyException
     */
    public function add(Money ...$money): Money
    {
        $this->assertSameCurrency(...$money);
        return static::fromCents(
            self::toMoneyPhp($this)->add(...array_map(self::toMoneyPhp(...), $money))->getAmount(),
            $this->getCurrency(),
        );
    }

    /**
     * @throws MoneyMismatchCurrencyException
     */
    public function subtract(Money ...$money): Money
    {
        $this->assertSameCurrency(...$money);
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
        return $this->getCents() === '0';
    }

    /**
     * @throws MoneyMismatchCurrencyException
     */
    public function equals(Money $money): bool
    {
        $this->assertSameCurrency($money);
        return $this->getDecimal() === $money->getDecimal();
    }

    /**
     * @throws MoneyMismatchCurrencyException
     */
    public function greaterThan(Money $money): bool
    {
        $this->assertSameCurrency($money);
        return self::toMoneyPhp($this)->greaterThan(self::toMoneyPhp($money));
    }

    /**
     * @throws MoneyMismatchCurrencyException
     */
    public function greaterThanOrEqual(Money $money): bool
    {
        $this->assertSameCurrency($money);
        return self::toMoneyPhp($this)->greaterThanOrEqual(self::toMoneyPhp($money));
    }

    /**
     * @throws MoneyMismatchCurrencyException
     */
    public function lessThan(Money $money): bool
    {
        $this->assertSameCurrency($money);
        return self::toMoneyPhp($this)->lessThan(self::toMoneyPhp($money));
    }

    /**
     * @throws MoneyMismatchCurrencyException
     */
    public function lessThanOrEqual(Money $money): bool
    {
        $this->assertSameCurrency($money);
        return self::toMoneyPhp($this)->lessThanOrEqual(self::toMoneyPhp($money));
    }

    public function compare(Money $money): int
    {
        $this->assertSameCurrency($money);
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

    /**
     * @throws MoneyMismatchCurrencyException
     */
    private function assertSameCurrency(Money ...$money): void
    {
        foreach ($money as $m) {
            if (! $this->getCurrency()->equals($m->getCurrency())) {
                throw MoneyMismatchCurrencyException::make(
                    expected: $this->getCurrency(),
                    actual: $m->getCurrency(),
                );
            }
        }
    }
}
