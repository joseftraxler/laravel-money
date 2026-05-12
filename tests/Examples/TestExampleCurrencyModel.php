<?php

declare(strict_types=1);

namespace Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use JosefTraxler\LaravelMoney\Contracts\Currencyable;

/**
 * @property non-empty-string $code
 * @property int $precision
 */
class TestExampleCurrencyModel extends Model implements Currencyable
{
    protected $guarded = [];
    protected function casts(): array
    {
        return [
            'code' => 'string',
            'precision' => 'int',
        ];
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
