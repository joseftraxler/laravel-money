<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

use JosefTraxler\LaravelMoney\Facades\MoneyDataProvider;

class Currency implements Contracts\Currency
{
    /** @var array<string, self> */
    private static array $instances = [];

    /** @var non-empty-string */
    private readonly string $code;

    /** @var non-negative-int */
    private int $precision;

    /** @param non-empty-string $code */
    final private function __construct(string $code)
    {
        $this->code = strtoupper($code);
    }

    /** @param non-empty-string|Contracts\Currency $code */
    final public static function of(string|Contracts\Currency $code): self
    {
        $code = $code instanceof Contracts\Currency ? $code->getCode() : strtoupper($code);
        return self::$instances[$code] ??= new Currency($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(Contracts\Currency $currency): bool
    {
        return $this->code === $currency->getCode();
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function getPrecision(): int
    {
        return $this->precision ??= MoneyDataProvider::precision($this->code);
    }
}
