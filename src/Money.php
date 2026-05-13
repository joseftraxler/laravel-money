<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use JosefTraxler\LaravelMoney\Traits\MoneyCalculator;
use JosefTraxler\LaravelMoney\Traits\MoneyCastable;
use JosefTraxler\LaravelMoney\Traits\MoneyFactory;
use JosefTraxler\LaravelMoney\Traits\MoneyHtmlable;
use JosefTraxler\LaravelMoney\Traits\MoneyNormalizer;
use JosefTraxler\LaravelMoney\Traits\MoneySerializable;

class Money implements
    Contracts\Money,
    Contracts\MoneyCastable,
    Contracts\MoneyJsonSerializable,
    \Stringable
{
    use MoneyCalculator;
    use MoneyCastable;
    use MoneyFactory;
    use MoneyHtmlable;
    use MoneyNormalizer;
    use MoneySerializable;

    /** @var numeric-string */
    private readonly string $decimal;
    private readonly Contracts\Currencyable $currency;

    /** @var numeric-string */
    private readonly string $cents;

    /**
     * @param numeric-string|float|int $decimal
     * @param Contracts\Currencyable|non-empty-string $currency
     */
    final public function __construct(
        string|float|int $decimal,
        Contracts\Currencyable|string $currency,
    ) {
        $this->currency = Currency::of($currency);
        $this->decimal = self::normalizeAmountInMajorUnit($decimal, $this->currency);
        $this->cents = self::decimalToCents($this->decimal);
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

    public function __toString(): string
    {
        return $this->toHumanReadable();
    }
}
