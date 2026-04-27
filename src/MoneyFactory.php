<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use Illuminate\Support\Facades\App;

final class MoneyFactory
{
    /**
     * @param non-empty-string $code
     */
    public static function makeCurrency(string|Contracts\Currency $code): Contracts\Currency
    {
        if ($code instanceof Contracts\Currency) {
            return $code;
        }
        /** @var Contracts\Currency */
        return App::make(Contracts\Currency::class, ['code' => $code]);
    }
}
