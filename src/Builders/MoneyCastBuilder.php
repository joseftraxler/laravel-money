<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Builders;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use JosefTraxler\LaravelMoney\Contracts\Currencyable;
use JosefTraxler\LaravelMoney\Contracts\Money;
use JosefTraxler\LaravelMoney\Exceptions\MoneyMismatchCurrencyException;

class MoneyCastBuilder
{
    /**
     * @param \Closure(mixed $value, Currencyable $currency): Money $getMoney
     * @param \Closure(Money $money): mixed $setMoney
     */
    public function __construct(
        private readonly \Closure $getMoney,
        private readonly \Closure $setMoney,
    ) {
    }

    /**
     * @param non-empty-string|Currencyable $currency
     * @return CastsAttributes<Money, Money>
     */
    public function withCurrency(string|Currencyable $currency): CastsAttributes
    {
        return $this->cast(
            fn (Model $model) => $currency,
            function (?Money $money) use ($currency) {
                if ( ! $money->getCurrency()->equals($currency)) {
                    throw new MoneyMismatchCurrencyException('Money must be in currency of ' . $currency);
                }
                return [];
            },
        );
    }

    /**
     * @param non-empty-string $column
     * @return CastsAttributes<Money, Money>
     */
    public function withCurrencyColumn(string $column): CastsAttributes
    {
        return $this->cast(
            fn (Model $model) => $model->getAttribute($column),
            fn (?Money $money): array => [
                $column => is_null($money) ? null : $money->getCurrency()->getCode(),
            ],
        );
    }

    /**
     * @param \Closure(Model $model): mixed $getCurrency
     * @param \Closure(?Money $money): array<string, mixed> $setCurrency
     *
     * @return CastsAttributes<Money,Money>
     */
    private function cast(\Closure $getCurrency, \Closure $setCurrency): CastsAttributes
    {
        return new class (
            $this->getMoney,
            $this->setMoney,
            $getCurrency,
            $setCurrency,
        ) implements CastsAttributes {
            /**
             * @param \Closure(mixed $value, Currencyable $currency): Money $getMoney
             * @param \Closure(Money $money): mixed $setMoney
             * @param \Closure(Model $model): mixed $getCurrency
             * @param \Closure(?Money $money): array<string, mixed> $setCurrency
             */
            public function __construct(
                private readonly \Closure $getMoney,
                private readonly \Closure $setMoney,
                private readonly \Closure $getCurrency,
                private readonly \Closure $setCurrency,
            ) {
            }

            public function get($model, string $key, $value, array $attributes)
            {
                if ($value === null) {
                    return null;
                }

                $currency = $this->getCurrency($model);

                if ($currency === null) {
                    return null;
                }

                return ($this->getMoney)($value, $currency);
            }

            public function set($model, string $key, $value, array $attributes)
            {
                if ($value === null) {
                    return null;
                }

                if (! $value instanceof Money) {
                    if ( ! is_numeric($value)) {
                        throw new \InvalidArgumentException('Value must be Money instance or a numeric value.');
                    }
                    return $value;
                }

                return [
                    $key => ($this->setMoney)($value),
                    ...($this->setCurrency)($value),
                ];
            }

            private function getCurrency(Model $model): ?Currencyable
            {
                $currency = ($this->getCurrency)($model);

                if ($currency === null || $currency === '') {
                    return null;
                }

                if (is_string($currency)) {
                    $currency = \JosefTraxler\LaravelMoney\Currency::of($currency);
                }

                if (! $currency instanceof Currencyable) {
                    throw new \InvalidArgumentException('Currency must be a string or instance of Currency');
                }

                return $currency;
            }
        };
    }
}
