<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\App;
use JosefTraxler\LaravelMoney\Casts\AsMoney;
use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\Contracts\MoneyFormatter;
use JosefTraxler\LaravelMoney\Traits\MoneyCalculator;

class Money implements Contracts\Money, Castable, Htmlable
{
    use MoneyCalculator;

    /** @var numeric-string */
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
     * @param float|numeric-string $decimal
     * @param non-empty-string|Currency $currency
     */
    public static function fromDecimal(float|string $decimal, string|Currency $currency): Contracts\Money
    {
        return MoneyFactory::makeMoneyFromDecimal($decimal, $currency);
    }

    /**
     * @param array{0?: non-empty-string, 1?: "decimal"|"cents"} $arguments
     * @return CastsAttributes<Contracts\Money, Contracts\Money>
     */
    public static function castUsing(array $arguments): CastsAttributes
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
        /** @var MoneyFormatter $formatter */
        $formatter = App::make(MoneyFormatter::class);
        return $formatter->humanReadable($this);
    }
}
