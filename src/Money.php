<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Htmlable;
use JosefTraxler\LaravelMoney\Traits\MoneyCalculator;
use JosefTraxler\LaravelMoney\Traits\MoneyCastable;
use JosefTraxler\LaravelMoney\Traits\MoneyFactory;
use JosefTraxler\LaravelMoney\Traits\MoneyHtmlable;
use JosefTraxler\LaravelMoney\Traits\MoneyNormalizer;

class Money implements Contracts\Money, Castable, Htmlable
{
    use MoneyCalculator;
    use MoneyCastable;
    use MoneyFactory;
    use MoneyHtmlable;
    use MoneyNormalizer;

    /** @var numeric-string */
    private readonly string $decimal;
    private readonly Contracts\Currency $currency;

    /** @var numeric-string */
    private readonly string $cents;

    /**
     * @param numeric-string|float|int $decimal
     * @param Contracts\Currency|non-empty-string $currency
     */
    final public function __construct(
        string|float|int $decimal,
        Contracts\Currency|string  $currency,
    ) {
        $this->currency = Currency::of($currency);
        $this->decimal = self::normalizeAmountInMajorUnit($decimal, $this->currency);
        /** @var numeric-string|"" $cents */
        $cents = ltrim(str_replace('.', '', $this->decimal), '0');
        $this->cents = $cents === '' ? '0' : $cents;
    }

    public function getDecimal(): string
    {
        return $this->decimal;
    }

    public function getCents(): string
    {
        return $this->cents;
    }

    public function getCurrency(): Contracts\Currency
    {
        return $this->currency;
    }
}
