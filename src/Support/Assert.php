<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Support;

/**
 * Runtime validation of values whose types are otherwise described only by PHPDoc
 * (e.g. positive-int, non-empty-array), so invalid input fails with a clear message
 * even without static analysis.
 *
 * @internal
 */
final class Assert
{
    private function __construct()
    {
    }

    /**
     * @param array<array-key, mixed> $value
     * @throws \InvalidArgumentException
     */
    public static function notEmpty(array $value, string $message): void
    {
        if ($value === []) {
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * Accepts an int, a finite float or a string in decimal notation (e.g. "1", "-0.5", "+2.25").
     *
     * @throws \InvalidArgumentException
     */
    public static function numeric(mixed $value, string $message): void
    {
        $valid = match (true) {
            is_int($value) => true,
            is_float($value) => is_finite($value),
            is_string($value) => preg_match('/^[+-]?\d+(\.\d+)?$/', $value) === 1,
            default => false,
        };
        if (! $valid) {
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @param int|float|numeric-string $value
     * @throws \InvalidArgumentException
     */
    public static function nonNegative(int|float|string $value, string $message): void
    {
        if (self::compareToZero($value) < 0) {
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @param int|float|numeric-string $value
     * @throws \InvalidArgumentException
     */
    public static function positive(int|float|string $value, string $message): void
    {
        if (self::compareToZero($value) <= 0) {
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * Strings are compared with bcmath, so arbitrarily large or precise values are not
     * rounded through a float.
     *
     * @param int|float|numeric-string $value
     */
    private static function compareToZero(int|float|string $value): int
    {
        if (is_string($value)) {
            return bccomp(ltrim($value, '+'), '0', strlen($value));
        }
        return $value <=> 0;
    }
}
