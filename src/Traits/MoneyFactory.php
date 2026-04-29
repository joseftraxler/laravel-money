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
 * @type TDecimal int|float|numeric-string
 *
 * @method static static USD(TDecimal $decimal = '0')
 * @method static static EUR(TDecimal $decimal = '0')
 * @method static static GBP(TDecimal $decimal = '0')
 * @method static static JPY(TDecimal $decimal = '0')
 * @method static static AUD(TDecimal $decimal = '0')
 * @method static static CAD(TDecimal $decimal = '0')
 * @method static static CHF(TDecimal $decimal = '0')
 * @method static static CNY(TDecimal $decimal = '0')
 * @method static static SEK(TDecimal $decimal = '0')
 * @method static static NZD(TDecimal $decimal = '0')
 * @method static static CZK(TDecimal $decimal = '0')
 * @method static static NOK(TDecimal $decimal = '0')
 * @method static static DKK(TDecimal $decimal = '0')
 * @method static static PLN(TDecimal $decimal = '0')
 * @method static static HKD(TDecimal $decimal = '0')
 * @method static static SGD(TDecimal $decimal = '0')
 * @method static static THB(TDecimal $decimal = '0')
 * @method static static MXN(TDecimal $decimal = '0')
 * @method static static BRL(TDecimal $decimal = '0')
 * @method static static ZAR(TDecimal $decimal = '0')
 * @method static static INR(TDecimal $decimal = '0')
 * @method static static RUB(TDecimal $decimal = '0')
 * @method static static TRY(TDecimal $decimal = '0')
 * @method static static KRW(TDecimal $decimal = '0')
 * @method static static IDR(TDecimal $decimal = '0')
 * @method static static MYR(TDecimal $decimal = '0')
 * @method static static PHP(TDecimal $decimal = '0')
 * @method static static TWD(TDecimal $decimal = '0')
 * @method static static AED(TDecimal $decimal = '0')
 * @method static static SAR(TDecimal $decimal = '0')
 * @method static static ILS(TDecimal $decimal = '0')
 * @method static static EGP(TDecimal $decimal = '0')
 * @method static static CLP(TDecimal $decimal = '0')
 * @method static static COP(TDecimal $decimal = '0')
 * @method static static ARS(TDecimal $decimal = '0')
 * @method static static PEN(TDecimal $decimal = '0')
 * @method static static VND(TDecimal $decimal = '0')
 * @method static static PKR(TDecimal $decimal = '0')
 * @method static static BGN(TDecimal $decimal = '0')
 * @method static static HRK(TDecimal $decimal = '0')
 * @method static static RON(TDecimal $decimal = '0')
 * @method static static UAH(TDecimal $decimal = '0')
 * @method static static NGN(TDecimal $decimal = '0')
 * @method static static KES(TDecimal $decimal = '0')
 * @method static static GHS(TDecimal $decimal = '0')
 * @method static static TND(TDecimal $decimal = '0')
 *
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyGettable
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyFactory
 */
trait MoneyFactory
{
    /**
     * @param float|numeric-string $decimal
     * @param non-empty-string|Contracts\Currencyable $currency
     */
    public static function fromDecimal(float|string $decimal, string|Contracts\Currencyable $currency): Contracts\Money
    {
        return new static($decimal, $currency);
    }

    /**
     * @param int|numeric-string $cents
     * @param non-empty-string|Contracts\Currencyable $currency
     * @return Contracts\Money
     */
    public static function fromCents(int|string $cents, string|Contracts\Currencyable $currency): Contracts\Money
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
            if (count($arguments) > 0) {
                /** @var array{0: int|float|numeric-string} $arguments */
                return static::fromCurrencyCode($name, $arguments[0]);
            }
            return static::fromCurrencyCode($name);
        }
        throw new \BadMethodCallException("Method {$name} does not exist in " . static::class);
    }

    /**
     * @param non-empty-string $code
     * @param TDecimal $decimal
     */
    private static function fromCurrencyCode(string $code, int|float|string $decimal = '0'): Contracts\Money
    {
        return static::fromDecimal($decimal, Currency::of($code));
    }
}
