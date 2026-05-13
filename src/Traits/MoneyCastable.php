<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Traits;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JosefTraxler\LaravelMoney\Casts\AsMoney;
use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Facades\MoneyDataProvider;

/**
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyCastable
 */
trait MoneyCastable
{
    /**
     * @param array{0?: non-empty-string, 1?: "decimal"|"cents"} $arguments
     * @return CastsAttributes<Contracts\Money, Contracts\Money>
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        $currency = (string) (
            $arguments[0]
            ?? MoneyDataProvider::defaultCurrency()?->getCode()
            ?? throw new \InvalidArgumentException('Money cast requires currency argument.')
        );

        $builder = match ($arguments[1] ?? 'decimal') {
            'cents' => AsMoney::inCents(),
            'decimal' => AsMoney::inDecimal(),
            default => throw new \InvalidArgumentException('Money cast storage must be "decimal" or "cents", ' . $arguments[1] . ' given.'),
        };

        if (preg_match('@^[A-Z]{3}$@', $currency)) {
            return $builder->withCurrency($currency);
        }
        return $builder->withCurrencyColumn($currency);
    }
}
