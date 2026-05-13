<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Traits;

use JosefTraxler\LaravelMoney\Contracts\Currency;

/**
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\Money
 */
trait MoneyNormalizer
{
    /** @return numeric-string */
    private static function normalizeAmountInMajorUnit(
        string|float|int $amountInMajorUnit,
        Currency $currency,
    ): string {
        return self::normalizeAmount($amountInMajorUnit, $currency->getPrecision());
    }

    /**
     * @param numeric-string $decimal
     * @return numeric-string
     */
    private static function decimalToCents(string $decimal): string
    {
        $numbers = str_replace(['.', '-'], '', (string) $decimal);
        $value = ltrim($numbers, '0');
        if ($value === '') {
            return '0';
        }
        $sign = $decimal[0] === '-' ? '-' : '';
        /** @phpstan-ignore return.type */
        return $sign . $value;
    }

    /** @return numeric-string */
    private static function centsToDecimal(int|string $cents, int $precision): string
    {
        if ($precision === 0) {
            /** @var numeric-string */
            return self::normalizeAmount($cents, 0);
        }
        $normalized = self::normalizeAmount($cents, 0);
        $sign = $normalized[0] === '-' ? '-' : '';
        $digits = ltrim($normalized, '-');
        if (strlen($digits) < $precision + 1) {
            $digits = str_pad($digits, $precision + 1, '0', STR_PAD_LEFT);
        }

        /** @var numeric-string */
        return $sign . substr_replace($digits, '.', strlen($digits) - $precision, 0);
    }

    /** @return numeric-string */
    private static function normalizeAmount(int|float|string $amount, int $precision): string
    {
        if (is_float($amount) || is_int($amount)) {
            /** @var numeric-string */
            return sprintf(
                '%.' . $precision . 'f',
                $amount,
            );
        }

        if (preg_match('/^[+-]?\d+(\.\d+)?$/', $amount) !== 1) {
            throw new \InvalidArgumentException('Amount must be a numeric-string or a float.');
        }

        $parts = explode('.', ltrim($amount, '+'));

        $sign = str_starts_with($parts[0], '-') ? '-' : '';
        $parts[0] = ltrim(ltrim($parts[0], '-'), '0');
        if ($parts[0] === '') {
            $parts[0] = '0';
        }
        $parts[0] = $sign . $parts[0];

        /** @var array{0: numeric-string, 1?: numeric-string} $parts */

        if ($precision === 0) {
            // converts negative zero to positive zero
            if ($parts[0] === '-0') {
                return '0';
            }
            return $parts[0];
        }
        $parts[1] ??= '0';

        $parts[1] = match (true) {
            strlen($parts[1]) === $precision => $parts[1],
            strlen($parts[1]) > $precision => substr($parts[1], 0, $precision),
            default => str_pad($parts[1], $precision, '0', STR_PAD_RIGHT),
        };

        // converts negative zero to positive zero
        if (trim($parts[0] . $parts[1], '0') === '-') {
            $parts[0] = '0';
        }

        /** @phpstan-ignore-next-line return.type */
        return $parts[0] . '.' . $parts[1];
    }
}
