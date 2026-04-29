<?php

namespace JosefTraxler\LaravelMoney\Traits;

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

    public static function fromJson(string $json): static
    {
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        if ( ! array_key_exists('decimal', $data) || ! array_key_exists('currency', $data))
        {
            throw new \InvalidArgumentException('Invalid JSON format for Money object');
        }

        return static::fromDecimal($data['decimal'], $data['currency']);
    }
}
