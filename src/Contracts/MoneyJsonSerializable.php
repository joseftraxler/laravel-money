<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Contracts;

interface MoneyJsonSerializable extends \JsonSerializable
{
    /** @return array{decimal: numeric-string, currency: non-empty-string} */
    public function jsonSerialize(): array;

    public function toJson(): string;

    public static function fromJson(string $json): static;
}
