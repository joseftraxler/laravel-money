<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JosefTraxler\LaravelMoney\Builders\MoneyCastBuilder;
use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Money;

final class AsMoney
{
    /**
     * @param null|non-empty-string|Contracts\Currencyable $currency
     *
     * @return ($currency is null ? MoneyCastBuilder : CastsAttributes<Contracts\Money,Contracts\Money>)
     */
    public static function inDecimal(
        null|string|Contracts\Currencyable $currency = null,
    ): MoneyCastBuilder|CastsAttributes {
        $builder = new MoneyCastBuilder(
            getMoney: function ($value, Contracts\Currencyable $currency): Contracts\Money {
                /** @var numeric-string $value */
                return Money::fromDecimal($value, $currency);
            },
            setMoney: function (Contracts\Money $money) {
                return $money->getDecimal();
            },
        );
        return is_null($currency) ? $builder : $builder->withCurrency($currency);
    }

    /**
     * @param null|non-empty-string|Contracts\Currencyable $currency
     *
     * @return ($currency is null ? MoneyCastBuilder : CastsAttributes<Contracts\Money,Contracts\Money>)
     */
    public static function inCents(
        null|string|Contracts\Currencyable $currency = null,
    ): MoneyCastBuilder|CastsAttributes {
        $builder = new MoneyCastBuilder(
            getMoney: function ($value, Contracts\Currencyable $currency): Contracts\Money {
                /** @var numeric-string $value */
                return Money::fromCents($value, $currency);
            },
            setMoney: function (Contracts\Money $money) {
                return $money->getDecimal();
            },
        );
        return is_null($currency) ? $builder : $builder->withCurrency($currency);
    }
}
