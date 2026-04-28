<?php

namespace JosefTraxler\LaravelMoney\Traits;

use JosefTraxler\LaravelMoney\Contracts;
use JosefTraxler\LaravelMoney\Currency;

/**
 * Provides factory methods for creating Money instances.
 *
 * This trait enables dynamic creation of Money objects using currency codes
 * as static method names, as well as directly creating Money instances from
 * a specified currency code and amount.
 *
 * @method static static USD(int|float|string $amount = '0')
 * @method static static EUR(int|float|string $amount = '0')
 * @method static static GBP(int|float|string $amount = '0')
 * @method static static JPY(int|float|string $amount = '0')
 * @method static static AUD(int|float|string $amount = '0')
 * @method static static CAD(int|float|string $amount = '0')
 * @method static static CHF(int|float|string $amount = '0')
 * @method static static CNY(int|float|string $amount = '0')
 * @method static static SEK(int|float|string $amount = '0')
 * @method static static NZD(int|float|string $amount = '0')
 * @method static static CZK(int|float|string $amount = '0')
 * @method static static NOK(int|float|string $amount = '0')
 * @method static static DKK(int|float|string $amount = '0')
 * @method static static PLN(int|float|string $amount = '0')
 * @method static static HKD(int|float|string $amount = '0')
 * @method static static SGD(int|float|string $amount = '0')
 * @method static static THB(int|float|string $amount = '0')
 * @method static static MXN(int|float|string $amount = '0')
 * @method static static BRL(int|float|string $amount = '0')
 * @method static static ZAR(int|float|string $amount = '0')
 * @method static static INR(int|float|string $amount = '0')
 * @method static static RUB(int|float|string $amount = '0')
 * @method static static TRY(int|float|string $amount = '0')
 * @method static static KRW(int|float|string $amount = '0')
 * @method static static IDR(int|float|string $amount = '0')
 * @method static static MYR(int|float|string $amount = '0')
 * @method static static PHP(int|float|string $amount = '0')
 * @method static static TWD(int|float|string $amount = '0')
 * @method static static AED(int|float|string $amount = '0')
 * @method static static SAR(int|float|string $amount = '0')
 * @method static static ILS(int|float|string $amount = '0')
 * @method static static EGP(int|float|string $amount = '0')
 * @method static static CLP(int|float|string $amount = '0')
 * @method static static COP(int|float|string $amount = '0')
 * @method static static ARS(int|float|string $amount = '0')
 * @method static static PEN(int|float|string $amount = '0')
 * @method static static VND(int|float|string $amount = '0')
 * @method static static PKR(int|float|string $amount = '0')
 * @method static static BGN(int|float|string $amount = '0')
 * @method static static HRK(int|float|string $amount = '0')
 * @method static static RON(int|float|string $amount = '0')
 * @method static static UAH(int|float|string $amount = '0')
 * @method static static NGN(int|float|string $amount = '0')
 * @method static static KES(int|float|string $amount = '0')
 * @method static static GHS(int|float|string $amount = '0')
 * @method static static TND(int|float|string $amount = '0')
 */
trait MoneyFactory
{
    public static function __callStatic(string $name, array $arguments)
    {
        if (strtoupper($name) === $name && strlen($name) === 3) {
            return static::fromCurrencyCode($name, ...$arguments);
        }
        throw new \BadMethodCallException("Method {$name} does not exist in " . static::class);
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

    public static function fromCurrencyCode(string $code, int|float|string $amount = '0'): static
    {
        return static::fromDecimal($amount, Currency::of($code));
    }
}
