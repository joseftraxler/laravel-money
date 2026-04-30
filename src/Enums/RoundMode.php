<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Enums;

use Money\Money;

enum RoundMode: int
{
    case Up = Money::ROUND_UP;
    case Down = Money::ROUND_DOWN;
    case HalfUp = Money::ROUND_HALF_UP;
    case HalfDown = Money::ROUND_HALF_DOWN;
    case HalfEven = Money::ROUND_HALF_EVEN;
}
