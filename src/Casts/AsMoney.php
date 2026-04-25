<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JosefTraxler\LaravelMoney\Builders\MoneyCastBuilder;
use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\Money;
use JosefTraxler\LaravelMoney\MoneyFactory;

final class AsMoney
{
    /**
     * @return ($currency is null ? MoneyCastBuilder : CastsAttributes)
     */
    public static function inDecimal(
        null|string|Currency $currency = null,
    ): MoneyCastBuilder|CastsAttributes {
        $builder = new MoneyCastBuilder(
            getMoney: fn ($value, Currency $currency): Money
                => MoneyFactory::makeMoneyFromDecimal($value, $currency),
            setMoney: fn (Money $money)
                => MoneyFactory::moneyToDecimal($money),
        );
        return is_null($currency) ? $builder : $builder->withCurrency($currency);
    }

    /**
     * @return ($currency is null ? MoneyCastBuilder : CastsAttributes)
     */
    public static function inCents(
        null|string|Currency $currency = null,
    ): MoneyCastBuilder|CastsAttributes {
        $builder = new MoneyCastBuilder(
            getMoney: fn ($value, Currency $currency): Money
                => MoneyFactory::makeMoney($value, $currency),
            setMoney: fn (Money $money)
                => $money->getAmount(),
        );
        return is_null($currency) ? $builder : $builder->withCurrency($currency);
    }
}
