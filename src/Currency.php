<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney;

class Currency implements Contracts\Currency
{
    /** @var array<string, self> */
    private static array $instances = [];

    /** @param non-empty-string $code */
    final private function __construct(
        private readonly string $code,
    ) {
    }

    /** @param non-empty-string $code */
    final public static function of(string $code): self
    {
        $code = strtoupper($code);
        return self::$instances[$code] ??= new static($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(\JosefTraxler\LaravelMoney\Contracts\Currency $currency): bool
    {
        return $this->code === $currency->getCode();
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
