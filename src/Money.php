<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Htmlable;
use JosefTraxler\LaravelMoney\Casts\AsMoney;
use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\Contracts\MoneyFormatter;
use JosefTraxler\LaravelMoney\Traits\MoneyCalculator;

class Money implements Contracts\Money, Castable, Htmlable
{
    use MoneyCalculator;

    private readonly string $amount;
    private readonly Currency $currency;

    final public function __construct(
        int|string $amount,
        Currency|string $currency,
    ) {
        $this->currency = ($currency instanceof Currency)
            ? $currency
            : MoneyFactory::makeCurrency($currency);
        $this->amount = (string) $amount;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param array{0?: string, 1?: "decimal"|"cents"} $arguments
     * @return \Illuminate\Contracts\Database\Eloquent\CastsAttributes|\Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes|string
     */
    public static function castUsing(array $arguments)
    {
        $currency = (string) (
            $arguments[0]
            ?? throw new \InvalidArgumentException('Money cast requires currency argument.')
        );

        $builder = match ($arguments[1] ?? 'decimal') {
            'cents' => AsMoney::inCents(),
            default => AsMoney::inDecimal(),
        };

        if (preg_match('@^[A-Z]{3}$@', $currency)) {
            return $builder->withCurrency($currency);
        }
        return $builder->withCurrencyColumn($currency);
    }

    public function toHtml()
    {
        return App::make(MoneyFormatter::class)->humanReadable($this);
    }
}
