<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Support;

/**
 * Splits an amount in minor units into parts that always add up exactly to the original
 * amount. All calculations are done in exact integer arithmetic using bcmath.
 *
 * @internal
 */
final class Allocator
{
    private function __construct()
    {
    }

    /**
     * Splits the amount by the given ratios. Leftover minor units go one by one to the parts
     * with the largest remainders, on equal remainders to the first ones. Negative amounts are
     * split symmetrically to positive ones (e.g. -5 / 3 => -2, -2, -1).
     *
     * @template TKey of array-key
     * @param numeric-string $amount amount in minor units
     * @param non-empty-array<TKey, int<0, max>|float|numeric-string> $ratios
     * @return non-empty-array<TKey, numeric-string> parts in minor units
     *
     * @throws \InvalidArgumentException
     */
    public static function allocate(string $amount, array $ratios): array
    {
        Assert::notEmpty($ratios, 'Cannot allocate to an empty list of ratios.');
        foreach ($ratios as $ratio) {
            Assert::numeric($ratio, 'Ratios must be integers, finite floats or numeric strings in decimal notation.');
            Assert::nonNegative($ratio, 'Ratios must be zero or positive.');
        }
        $ratios = self::ratiosToIntegers($ratios);
        $total = self::sum($ratios);
        Assert::positive($total, 'Sum of ratios must be greater than zero.');

        $negative = bccomp($amount, '0', 0) < 0;
        $amount = $negative ? bcsub('0', $amount, 0) : $amount;

        $shares = [];
        $remainders = [];
        foreach ($ratios as $key => $ratio) {
            $product = bcmul($amount, $ratio, 0);
            $shares[$key] = bcdiv($product, $total, 0);
            $remainders[$key] = bcmod($product, $total, 0);
        }

        // uasort is stable, so on equal remainders the first parts keep precedence
        $leftover = (int) bcsub($amount, self::sum($shares), 0);
        uasort($remainders, static fn (string $a, string $b): int => bccomp($b, $a, 0));
        foreach (array_slice(array_keys($remainders), 0, $leftover) as $key) {
            $shares[$key] = bcadd($shares[$key], '1', 0);
        }

        return $negative
            ? array_map(static fn (string $share): string => bcsub('0', $share, 0), $shares)
            : $shares;
    }

    /**
     * Splits the amount into n equal parts, leftover minor units go to the first parts.
     *
     * @param numeric-string $amount amount in minor units
     * @param positive-int $n
     * @return non-empty-list<numeric-string> parts in minor units
     *
     * @throws \InvalidArgumentException
     */
    public static function allocateTo(string $amount, int $n): array
    {
        Assert::positive($n, 'Number of parts must be greater than zero.');
        return array_values(self::allocate($amount, array_fill(0, $n, 1)));
    }

    /**
     * Converts the ratios to integers with the same proportions
     * (e.g. [0.5, '1.25', 2] => ['50', '125', '200']). Floats are taken with 14 decimal places.
     *
     * @template TKey of array-key
     * @param non-empty-array<TKey, int|float|numeric-string> $ratios
     * @return non-empty-array<TKey, numeric-string>
     */
    private static function ratiosToIntegers(array $ratios): array
    {
        $decimals = array_map(self::ratioToDecimal(...), $ratios);
        $scale = max(array_map(
            static fn (string $decimal): int => strlen(strrchr($decimal, '.') ?: '.') - 1,
            $decimals,
        ));
        $multiplier = bcpow('10', (string) $scale, 0);

        return array_map(
            static fn (string $decimal): string => bcmul($decimal, $multiplier, 0),
            $decimals,
        );
    }

    /**
     * @param int|float|numeric-string $ratio
     * @return numeric-string
     */
    private static function ratioToDecimal(int|float|string $ratio): string
    {
        if (is_int($ratio)) {
            return (string) $ratio;
        }
        if (is_float($ratio)) {
            $ratio = rtrim(rtrim(sprintf('%.14F', $ratio), '0'), '.');
        }
        /** @var numeric-string */
        return ltrim($ratio, '+');
    }

    /**
     * @param array<array-key, numeric-string> $numbers
     * @return numeric-string
     */
    private static function sum(array $numbers): string
    {
        return array_reduce(
            $numbers,
            static fn (string $sum, string $number): string => bcadd($sum, $number, 0),
            '0',
        );
    }
}
