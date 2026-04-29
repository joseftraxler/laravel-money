<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Providers;

use Illuminate\Contracts\Config\Repository;
use JosefTraxler\LaravelMoney\Contracts\Currency;
use JosefTraxler\LaravelMoney\Contracts\MoneyDataProvider as MoneyDataProviderInterface;

class MoneyDataProvider implements MoneyDataProviderInterface
{
    /** @var array<non-empty-string, non-negative-int> */
    private array $precisionCache = [];

    public function __construct(
        private readonly Repository $config,
    ) {
    }

    public function defaultCurrency(): ?Currency
    {
        $defaultCode = $this->config->get('money.default_currency');
        if (is_null($defaultCode) || $defaultCode === '') {
            return null;
        }
        if (! is_string($defaultCode)) {
            throw new \UnexpectedValueException("Invalid default currency, value must be a string.");
        }
        return \JosefTraxler\LaravelMoney\Currency::of($defaultCode);
    }

    public function precision(Currency $currency): int
    {
        return $this->precisionCache[$currency->getCode()] ??= $this->resolvePrecision($currency);
    }

    /** @return non-negative-int */
    private function resolvePrecision(Currency $currency): int
    {
        $result = filter_var(
            $this->config->get('money.currencies.' . $currency->getCode() . '.precision')
                ?? $this->getDefaultPrecision($currency),
            FILTER_VALIDATE_INT,
            FILTER_NULL_ON_FAILURE,
        ) ?? throw new \UnexpectedValueException("Invalid precision for currency {$currency->getCode()}, value must be an integer.");

        if ($result < 0) {
            throw new \UnexpectedValueException("Invalid precision for currency {$currency->getCode()}, value must be greater than or equal to 0.");
        }

        return $result;
    }

    public function getDefaultPrecision(Currency $currency): int
    {
        $result = filter_var(
            $this->config->get(
                'money.default_precision',
                $this->detectDefaultPrecision($currency),
            ),
            FILTER_VALIDATE_INT,
            FILTER_NULL_ON_FAILURE,
        ) ?? throw new \UnexpectedValueException("Invalid default precision, value must be an integer.");

        if ($result < 0) {
            throw new \UnexpectedValueException("Invalid default precision, value must be greater than or equal to 0.");
        }

        return $result;
    }

    private function detectDefaultPrecision(Currency $currency): int
    {
        return match ($currency->getCode()) {
            // Zero decimal currencies
            'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF' => 0,
            // Three decimal currencies
            'BHD', 'IQD', 'JOD', 'KWD', 'LYD', 'OMR', 'TND' => 3,
            default => 2,
        };
    }
}
