<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface Money extends
    MoneyGettable,
    MoneyCalculator,
    MoneyFactory,
    MoneyHtmlable
{
}
