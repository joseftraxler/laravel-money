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

    public function toCentsUnit(string|Currency $currency): int
    {
        return match ($this) {
            self::Cent => 0,
            self::TenCents => 1,
            self::HundredCents => 2,
            self::ThousandCents => 3,
            self::TenThousandsCents => 4,
            default => null
        } ?? MoneyFactory::currencyPrecision($currency) + match ($this) {
            self::MajorUnit => 0,
            self::Ten => 1,
            self::Hundred => 2,
            self::Thousand => 3,
            self::TenThousands => 4,
            self::HundredThousands => 5,
            self::Million => 6,
            self::TenMillion => 7,
            self::HundredMillion => 8,
            self::Billion => 9,
            self::TenBillion => 10,
            self::HundredBillion => 11,
            self::Trillion => 12,
        };
    }

}
