<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Formatters;

use Illuminate\Support\Facades\App;
use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\Contracts\Money;
use JosefTraxler\LaravelMoney\MoneyFactory;

class MoneyFormatter implements \JosefTraxler\LaravelMoney\Contracts\MoneyFormatter
{
    public function humanReadableAmount(Money $money): string
    {
        return (string) self::currencyFormatter()->format(
            (float) MoneyFactory::moneyToDecimal($money),
        );
    }

    public function humanReadableCurrency(Currency|Money $currencyOrMoney): string
    {
        /** @var array<string, string> $cache */
        static $cache = [];

        $money = $currencyOrMoney instanceof Money
            ? $currencyOrMoney
            : MoneyFactory::makeMoney(0, $currencyOrMoney);

        return $cache[$money->getCurrency()->getCode()] ??= trim(str_replace(
            self::humanReadableAmount($money),
            '',
            self::humanReadable($money),
        ));
    }

    public function humanReadable(Money $money): string
    {
        return (string) self::currencyFormatter()->formatCurrency(
            (float) MoneyFactory::moneyToDecimal($money),
            $money->getCurrency()->getCode(),
        );
    }

    protected static function currencyFormatter(): \NumberFormatter
    {
        /** @var ?\NumberFormatter $formatter */
        static $formatter;

        return $formatter ??= new \NumberFormatter(App::getLocale(), \NumberFormatter::CURRENCY);
    }
}
