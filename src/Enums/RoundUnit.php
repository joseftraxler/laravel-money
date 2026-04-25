<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Enums;

use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\MoneyFactory;

enum RoundUnit
{
    case Cent;
    case TenCents;
    case HundredCents;
    case ThousandCents;
    case TenThousandsCents;
    case MajorUnit;
    case Ten;
    case Hundred;
    case Thousand;
    case TenThousands;
    case HundredThousands;
    case Million;
    case TenMillion;
    case HundredMillion;
    case Billion;
    case TenBillion;
    case HundredBillion;
    case Trillion;

    /** @return positive-int */
    public function toCentsUnit(string|Currency $currency): int
    {
        /** @var positive-int */
        return match ($this) {
            self::Cent => 0,
            self::TenCents => 1,
            self::HundredCents => 2,
            self::ThousandCents => 3,
            self::TenThousandsCents => 4,
            self::MajorUnit => 0 + MoneyFactory::currencyPrecision($currency),
            self::Ten => 1 + MoneyFactory::currencyPrecision($currency),
            self::Hundred => 2 + MoneyFactory::currencyPrecision($currency),
            self::Thousand => 3 + MoneyFactory::currencyPrecision($currency),
            self::TenThousands => 4 + MoneyFactory::currencyPrecision($currency),
            self::HundredThousands => 5 + MoneyFactory::currencyPrecision($currency),
            self::Million => 6 + MoneyFactory::currencyPrecision($currency),
            self::TenMillion => 7 + MoneyFactory::currencyPrecision($currency),
            self::HundredMillion => 8 + MoneyFactory::currencyPrecision($currency),
            self::Billion => 9 + MoneyFactory::currencyPrecision($currency),
            self::TenBillion => 10 + MoneyFactory::currencyPrecision($currency),
            self::HundredBillion => 11 + MoneyFactory::currencyPrecision($currency),
            self::Trillion => 12 + MoneyFactory::currencyPrecision($currency),
        };
    }

}
