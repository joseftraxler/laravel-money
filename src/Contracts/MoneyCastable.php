<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

interface MoneyCastable extends Castable
{
    /**
     * @param array{0?: non-empty-string, 1?: "decimal"|"cents"} $arguments
     * @return CastsAttributes<Money, Money>
     */
    public static function castUsing(array $arguments): CastsAttributes;
}
