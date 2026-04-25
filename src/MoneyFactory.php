<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

final class MoneyFactory
{
    public static function makeMoney(int|string $cents, string|Contracts\Currency $code): Contracts\Money
    {
        return App::make(Contracts\Money::class, [
            'cents' => $cents,
            'currency' => $code instanceof Contracts\Currency ? $code : self::makeCurrency($code),
        ]);
    }

    public static function makeMoneyFromDecimal(string|float $decimal, string|Contracts\Currency $code): Contracts\Money
    {
        return self::makeMoney(
            self::decimalToCents($decimal, $code),
            $code,
        );
    }
    public static function makeCurrency(string $code): Contracts\Currency
    {
        return App::make(Contracts\Currency::class, ['code' => $code]);
    }

    /**
     * @param numeric-string|float $decimal
     * @return numeric-string
     */
    public static function decimalToCents(string|float $decimal, string|Contracts\Currency $code): string
    {
        $currencyCode = $code instanceof Contracts\Currency ? $code->getCode() : $code;
        $precision = (int)Config::get('money.precisions.' . $currencyCode, 2);

        $multiplier = 10 ** $precision;

        return sprintf('%d', round((float) $decimal * $multiplier));
    }

    /**
     * @return numeric-string
     */
    public static function moneyToDecimal(Contracts\Money $money): string
    {
        $precision = (int) Config::get('money.precisions.' . $money->getCurrency()->getCode(), 2);

        $multiplier = 10 ** $precision;

        return sprintf('%0.' . $precision . 'f', $money->getAmount() / $multiplier);
    }
}
