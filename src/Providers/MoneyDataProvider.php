<?php

declare(strict_types=1);

namespace JosefTraxler\LaravelMoney\Providers;

use Illuminate\Contracts\Config\Repository;
use JosefTraxler\LaravelMoney\Contracts\Currency;

class MoneyDataProvider implements \JosefTraxler\LaravelMoney\Contracts\MoneyDataProvider
{
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

    public function precision(string $currencyCode): int
    {
        $result = filter_var(
            $this->config->get(
                'money.currencies.' . $currencyCode,
                $this->getDefaultPrecision($currencyCode),
            ),
            FILTER_VALIDATE_INT,
            FILTER_NULL_ON_FAILURE,
        ) ?? throw new \UnexpectedValueException("Invalid precision for currency {$currencyCode}, value must be an integer.");
        if ($result < 0) {
            throw new \UnexpectedValueException("Invalid precision for currency {$currencyCode}, value must be greater than or equal to 0.");
        }
        return $result;
    }

    public function getDefaultPrecision(string $currencyCode): int
    {
        $result = filter_var(
            $this->config->get(
                'money.default_precision',
                $this->detectDefaultPrecision($currencyCode),
            ),
            FILTER_VALIDATE_INT,
            FILTER_NULL_ON_FAILURE,
        ) ?? throw new \UnexpectedValueException("Invalid default precision, value must be an integer.");

        if ($result < 0) {
            throw new \UnexpectedValueException("Invalid default precision, value must be greater than or equal to 0.");
        }

        return $result;
    }

    private function detectDefaultPrecision(string $currencyCode): int
    {
        return match ($currencyCode) {
            // Zero decimal currencies
            'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF' => 0,
            // Three decimal currencies
            'BHD', 'IQD', 'JOD', 'KWD', 'LYD', 'OMR', 'TND' => 3,
            default => 2,
        };
    }

    public function getCurrencyClassName(): string
    {
        $configClass = $this->config->get('money.currency_class');
        if ($configClass === null) {
            return \JosefTraxler\LaravelMoney\Currency::class;
        }
        if (! is_string($configClass)) {
            throw new \UnexpectedValueException("Invalid currency class name, value must be a string.");
        }

        if (! is_subclass_of($configClass, Currency::class)) {
            throw new \UnexpectedValueException("Invalid currency class name, value must be a subclass of " . Currency::class);
        }

        return $configClass;
    }
}
