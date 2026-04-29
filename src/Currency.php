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

    /** @param non-empty-string $code */
    final private function __construct(string $code)
    {
        $this->code = strtoupper($code);
    }

    /**
     * Returns a currency instance by its ISO 4217 code.
     *
     * @param non-empty-string|Contracts\Currencyable $code
     */
    final public static function of(string|Contracts\Currencyable $code): Contracts\Currency
    {
        $code = strtoupper($code instanceof Contracts\Currencyable ? $code->getCode() : $code);

        if (preg_match('/^[A-Z]{3}$/', $code) !== 1) {
            throw new \InvalidArgumentException('Currency code must be a valid ISO 4217 code.');
        }

        return self::$instances[$code] ??= new Currency($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(Contracts\Currencyable|string $currency): bool
    {
        if ($currency instanceof Contracts\Currencyable) {
            $currency = $currency->getCode();
        }
        return $this->code === strtoupper($currency);
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function getPrecision(): int
    {
        return MoneyDataProvider::precision($this->code);
    }
}
