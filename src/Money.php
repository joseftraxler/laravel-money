<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Htmlable;
use JosefTraxler\LaravelMoney\Traits\MoneyCalculator;
use JosefTraxler\LaravelMoney\Traits\MoneyCastable;
use JosefTraxler\LaravelMoney\Traits\MoneyHtmlable;
use JosefTraxler\LaravelMoney\Traits\MoneyNormalizer;

class Money implements Contracts\Money, Castable, Htmlable
{
    use MoneyCalculator;
    use MoneyCastable;
    use MoneyNormalizer;
    use MoneyHtmlable;

    /** @var numeric-string */
    private readonly string $decimal;
    private readonly Contracts\Currency $currency;

    /** @var numeric-string */
    private string $cents;

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
        /** @phpstan-ignore-next-line assign.propertyType */
        $this->cents = str_replace('.', '', $this->decimal);
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

    /**
     * @param float|numeric-string $decimal
     * @param non-empty-string|Contracts\Currency $currency
     */
    public static function fromDecimal(float|string $decimal, string|Contracts\Currency $currency): Contracts\Money
    {
        return new static($decimal, $currency);
    }

    /**
     * @param int|numeric-string $cents
     * @param non-empty-string|Contracts\Currency $currency
     * @return Contracts\Money
     */
    public static function fromCents(int|string $cents, string|Contracts\Currency $currency): Contracts\Money
    {
        $currency = Currency::of($currency);
        return new static(
            self::centsToDecimal($cents, $currency->getPrecision()),
            $currency,
        );
    }
}
