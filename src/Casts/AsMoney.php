<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JosefTraxler\LaravelMoney\Builders\MoneyCastBuilder;
use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\Contracts\Money;
use JosefTraxler\LaravelMoney\MoneyFactory;

final class AsMoney
{
    /**
     * @param null|non-empty-string|Currency $currency
     *
     * @return ($currency is null ? MoneyCastBuilder : CastsAttributes<Money,Money>)
     */
    public static function inDecimal(
        null|string|Currency $currency = null,
    ): MoneyCastBuilder|CastsAttributes {
        $builder = new MoneyCastBuilder(
            getMoney: function ($value, Currency $currency): Money {
                /** @var numeric-string $value */
                return MoneyFactory::makeMoneyFromDecimal($value, $currency);
            },
            setMoney: function (Money $money) {
                return MoneyFactory::moneyToDecimal($money);
            },
        );
        return is_null($currency) ? $builder : $builder->withCurrency($currency);
    }

    /**
     * @param null|non-empty-string|Currency $currency
     *
     * @return ($currency is null ? MoneyCastBuilder : CastsAttributes<Money,Money>)
     */
    public static function inCents(
        null|string|Currency $currency = null,
    ): MoneyCastBuilder|CastsAttributes {
        $builder = new MoneyCastBuilder(
            getMoney: function ($value, Currency $currency): Money {
                /** @var numeric-string $value */
                return MoneyFactory::makeMoney($value, $currency);
            },
            setMoney: function (Money $money) {
                return $money->getAmount();
            },
        );
        return is_null($currency) ? $builder : $builder->withCurrency($currency);
    }
}
