<?php

declare(strict_types=1);

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
 * @method static static USD(int|float|numeric-string $decimal = '0')
 * @method static static EUR(int|float|numeric-string $decimal = '0')
 * @method static static GBP(int|float|numeric-string $decimal = '0')
 * @method static static JPY(int|float|numeric-string $decimal = '0')
 * @method static static AUD(int|float|numeric-string $decimal = '0')
 * @method static static CAD(int|float|numeric-string $decimal = '0')
 * @method static static CHF(int|float|numeric-string $decimal = '0')
 * @method static static CNY(int|float|numeric-string $decimal = '0')
 * @method static static SEK(int|float|numeric-string $decimal = '0')
 * @method static static NZD(int|float|numeric-string $decimal = '0')
 * @method static static CZK(int|float|numeric-string $decimal = '0')
 * @method static static NOK(int|float|numeric-string $decimal = '0')
 * @method static static DKK(int|float|numeric-string $decimal = '0')
 * @method static static PLN(int|float|numeric-string $decimal = '0')
 * @method static static HKD(int|float|numeric-string $decimal = '0')
 * @method static static SGD(int|float|numeric-string $decimal = '0')
 * @method static static THB(int|float|numeric-string $decimal = '0')
 * @method static static MXN(int|float|numeric-string $decimal = '0')
 * @method static static BRL(int|float|numeric-string $decimal = '0')
 * @method static static ZAR(int|float|numeric-string $decimal = '0')
 * @method static static INR(int|float|numeric-string $decimal = '0')
 * @method static static RUB(int|float|numeric-string $decimal = '0')
 * @method static static TRY(int|float|numeric-string $decimal = '0')
 * @method static static KRW(int|float|numeric-string $decimal = '0')
 * @method static static IDR(int|float|numeric-string $decimal = '0')
 * @method static static MYR(int|float|numeric-string $decimal = '0')
 * @method static static PHP(int|float|numeric-string $decimal = '0')
 * @method static static TWD(int|float|numeric-string $decimal = '0')
 * @method static static AED(int|float|numeric-string $decimal = '0')
 * @method static static SAR(int|float|numeric-string $decimal = '0')
 * @method static static ILS(int|float|numeric-string $decimal = '0')
 * @method static static EGP(int|float|numeric-string $decimal = '0')
 * @method static static CLP(int|float|numeric-string $decimal = '0')
 * @method static static COP(int|float|numeric-string $decimal = '0')
 * @method static static ARS(int|float|numeric-string $decimal = '0')
 * @method static static PEN(int|float|numeric-string $decimal = '0')
 * @method static static VND(int|float|numeric-string $decimal = '0')
 * @method static static PKR(int|float|numeric-string $decimal = '0')
 * @method static static BGN(int|float|numeric-string $decimal = '0')
 * @method static static HRK(int|float|numeric-string $decimal = '0')
 * @method static static RON(int|float|numeric-string $decimal = '0')
 * @method static static UAH(int|float|numeric-string $decimal = '0')
 * @method static static NGN(int|float|numeric-string $decimal = '0')
 * @method static static KES(int|float|numeric-string $decimal = '0')
 * @method static static GHS(int|float|numeric-string $decimal = '0')
 * @method static static TND(int|float|numeric-string $decimal = '0')
 *
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\Money
 */
trait MoneyFactory
{
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

    /**
     * @param string $name
     * @param array<array-key, mixed> $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (strtoupper($name) === $name && strlen($name) === 3) {
            if (count($arguments) > 1) {
                /** @var array{0: int|float|numeric-string} $arguments */
                return static::fromCurrencyCode($name, $arguments[0]);
            }
            return static::fromCurrencyCode($name);
        }
        throw new \BadMethodCallException("Method {$name} does not exist in " . static::class);
    }

    /**
     * @param non-empty-string $code
     * @param int|float|numeric-string $decimal
     */
    public static function fromCurrencyCode(string $code, int|float|string $decimal = '0'): Contracts\Money
    {
        return static::fromDecimal($decimal, Currency::of($code));
    }
}
