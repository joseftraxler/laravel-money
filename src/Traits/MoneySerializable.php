<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Traits;

use JosefTraxler\LaravelMoney\Contracts\Money;

/**
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyGettable
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyFactory
 * @phpstan-require-implements \JosefTraxler\LaravelMoney\Contracts\MoneyJsonSerializable
 */
trait MoneySerializable
{
    public function jsonSerialize(): array
    {
        return [
            'decimal' => $this->getDecimal(),
            'currency' => $this->getCurrency()->getCode(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->jsonSerialize(), flags: JSON_THROW_ON_ERROR);
    }

    public static function fromJson(string $json): Money
    {
        $data = (array) json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        if (! array_key_exists('decimal', $data) || ! array_key_exists('currency', $data)) {
            throw new \InvalidArgumentException('JSON data must contain "decimal" and "currency" keys');
        }
        if (! is_numeric($data['decimal'])) {
            throw new \InvalidArgumentException('JSON decimal must be numeric');
        }
        if (! is_string($data['currency']) || $data['currency'] === '') {
            throw new \InvalidArgumentException('JSON currency must be a non-empty string');
        }

        return static::fromDecimal($data['decimal'], $data['currency']);
    }
}
