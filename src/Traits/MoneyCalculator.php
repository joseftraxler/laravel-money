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

    public function allocate(array $ratios): array
    {
        if ($ratios === []) {
            throw new \InvalidArgumentException('Cannot allocate to an empty list of ratios');
        }
        foreach ($ratios as $ratio) {
            /** @phpstan-ignore function.alreadyNarrowedType, booleanAnd.alwaysFalse */
            if (! is_int($ratio) && ! is_float($ratio)) {
                throw new \InvalidArgumentException('Ratios must be integers or floats');
            }
            if ($ratio < 0) {
                throw new \InvalidArgumentException('Ratios must be zero or positive');
            }
        }
        if (array_sum($ratios) <= 0) {
            throw new \InvalidArgumentException('Sum of ratios must be greater than zero');
        }

        // allocate the absolute amount so negative amounts are split
        // symmetrically to positive ones (e.g. -0.05 / 3 => -0.02, -0.02, -0.01)
        $negative = $this->isNegative();
        $moneyPhp = self::toMoneyPhp($this);
        if ($negative) {
            $moneyPhp = $moneyPhp->absolute();
        }

        $allocated = $moneyPhp->allocate($ratios);
        $parts = [];
        foreach (array_keys($ratios) as $key) {
            $part = $negative ? $allocated[$key]->negative() : $allocated[$key];
            $parts[$key] = static::fromCents($part->getAmount(), $this->getCurrency());
        }
        return $parts;
    }

    public function allocateTo(int $n): array
    {
        if ($n < 1) {
            throw new \InvalidArgumentException('Number of parts must be greater than zero');
        }
        return array_values($this->allocate(array_fill(0, $n, 1)));
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
