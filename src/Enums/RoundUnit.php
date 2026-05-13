<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Enums;

use JosefTraxler\LaravelMoney\Contracts\Currency;

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

    /** @return non-negative-int */
    public function toCentsUnit(Currency $currency): int
    {
        return match ($this) {
            self::Cent => 0,
            self::TenCents => 1,
            self::HundredCents => 2,
            self::ThousandCents => 3,
            self::TenThousandsCents => 4,
            self::MajorUnit => 0 + $currency->getPrecision(),
            self::Ten => 1 + $currency->getPrecision(),
            self::Hundred => 2 + $currency->getPrecision(),
            self::Thousand => 3 + $currency->getPrecision(),
            self::TenThousands => 4 + $currency->getPrecision(),
            self::HundredThousands => 5 + $currency->getPrecision(),
            self::Million => 6 + $currency->getPrecision(),
            self::TenMillion => 7 + $currency->getPrecision(),
            self::HundredMillion => 8 + $currency->getPrecision(),
            self::Billion => 9 + $currency->getPrecision(),
            self::TenBillion => 10 + $currency->getPrecision(),
            self::HundredBillion => 11 + $currency->getPrecision(),
            self::Trillion => 12 + $currency->getPrecision(),
        };
    }

}
