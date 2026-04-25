<?php

declare(strict_types=1);

namespace Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use JosefTraxler\LaravelMoney\Money;

/**
 * @property Money $price_eur
 * @property Money $paid
 * @property Money $paid_in_cents
 * @property string $paid_currency
 */
class ModelExample extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {
        return [
            'price_eur' => Money::class . ':EUR',
            'paid' => Money::class . ':paid_currency',
            'paid_in_cents' => Money::class . ':paid_currency,cents',
            'paid_currency' => 'string',
        ];
    }

    public function getRawAttribute($key): mixed
    {
        return $this->attributes[$key] ?? null;
    }
}
