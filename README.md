# Laravel Money

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joseftraxler/laravel-money.svg?style=flat-square)](https://packagist.org/packages/joseftraxler/laravel-money)
[![Total Downloads](https://img.shields.io/packagist/dt/joseftraxler/laravel-money.svg?style=flat-square)](https://packagist.org/packages/joseftraxler/laravel-money)
![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=flat-square&logo=php)
![Laravel Version](https://img.shields.io/badge/Laravel-12%2B-FF2D20?style=flat-square&logo=laravel)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)
[![CI](https://github.com/joseftraxler/laravel-money/actions/workflows/CI.yml/badge.svg)](https://github.com/joseftraxler/laravel-money/actions/workflows/CI.yml)

A lightweight Laravel package for working with money values, currencies, formatting, arithmetic operations, and Eloquent casts.

Laravel Money provides a small immutable `Money` value object with currency-aware precision handling and convenient integration for Laravel applications.

## Features

- Immutable `Money` value object
- ISO 4217-style currency code validation
- Currency-specific precision support
- Decimal and cents-based factory methods
- Arithmetic operations powered by [`moneyphp/money`](https://github.com/moneyphp/money)
- Human-readable formatting using PHP `intl`
- Eloquent casts for decimal and cents database columns
- Support for fixed currency and currency-column casts
- Laravel auto-discovery
- Publishable configuration

## Requirements

- PHP 8.4+
- Laravel 12+
- PHP `intl` extension

## Installation

Install the package via Composer:

```bash
composer require joseftraxler/laravel-money
```

The service provider is registered automatically through Laravel package discovery.

## Configuration

The package works without publishing configuration. If you want to customize default currency or precision settings, publish the config file:

```bash
php artisan vendor:publish --tag=money-config
```

This publishes: [config/money.php](config/money.php)

Example configuration:

```php
return [
    'default_currency' => 'USD', // default currency used by casts when no currency is provided
    'default_precision' => null, // null = automatically detected
    'currencies' => [
        'JPY' => ['precision' => 0],
        'BHD' => ['precision' => 3],
    ],
];
```

## Basic usage

```php
use JosefTraxler\LaravelMoney\Money;

$money = Money::EUR('10.45');
$money->getDecimal(); // "10.45"
$money->getCents(); // "1045"
$money->getCurrency()->getCode(); // "EUR"

$twoPieces = $money->multiply(2)->getDecimal(); // "20.90"

$product->price = $money;
$product->save();

$product->price->toHumanReadable(locale: 'en_US'); // e.g.: "€10.45"
```

In Blade:

```blade
{{ $product->price }}
```

Currency codes are strict ISO 4217-style codes. They must contain exactly three letters.

### Currency precision

Precision is resolved in this order:

1. `money.currencies.{CODE}.precision`
2. `money.default_precision`
3. package-detected default precision

Precision values are validated and cached per currency for performance.

> ⚠️ When a decimal value contains more fractional digits than the currency precision allows,
the value is truncated to the configured precision.

## Creating money values

### From decimal

```php
use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

new Money('123.45', 'EUR');
Money::fromDecimal('123.45', 'EUR');
Money::EUR('123.45');
Money::fromDecimal('123.45', Currency::of('EUR'));
```

### From cents

```php
Money::fromCents(12345, 'EUR'); // 123.45 EUR
```

### Numeric strings are recommended

The package accepts integers, floats, and numeric strings for convenience:

```php
Money::fromDecimal(10, 'EUR');
Money::fromDecimal(10.50, 'EUR');
Money::fromDecimal('10.50', 'EUR'); // recommended
```

For exact monetary input, prefer numeric strings:

```php
Money::fromDecimal('10.50', 'EUR');
```

### Using custom currency class/enum

You can also use a custom currency class/enum that implements `JosefTraxler\LaravelMoney\Contracts\Currencyable`:

```php
enum MyCurrency: string implements JosefTraxler\LaravelMoney\Contracts\Currencyable
{
    case EUR = 'EUR';
    case USD = 'USD';

    public function getCode(): string
    {
        return $this->value;
    }
}

Money::fromDecimal('123.45', MyCurrency::EUR);
```

or some model e.g.:

```php
class Currency extends Model implements JosefTraxler\LaravelMoney\Contracts\Currencyable
{
    public function getCode(): string
    {
        return $this->code;
    }
}

$currency = new Currency(['code' => 'EUR']);
Money::fromDecimal('123.45', $currency);
```

## Arithmetic

Money values support common arithmetic operations.

```php
$ten = Money::EUR('10.00');
$fiveFifty = Money::EUR('5.50');
$ten->add($fiveFifty)->getDecimal(); // "15.50"
$ten->subtract($fiveFifty)->getDecimal(); // "4.50"
$ten->multiply(2)->getDecimal(); // "20.00"
$ten->divide(2)->getDecimal(); // "5.00"
```

Arithmetic operations are currency-aware and delegated to `moneyphp/money`.

## Comparisons

```php
$ten = Money::EUR('10.00');
$twenty = Money::EUR('20.00');
$ten->equals($twenty); // false
$ten->lessThan($twenty); // true
$twenty->greaterThan($ten); // true
$ten->compare($twenty); // -1
```

Predicates are also available:

```php
Money::EUR('10.00')->isPositive(); // true
Money::EUR('-10.00')->isNegative(); // true
Money::EUR('0.00')->isZero(); // true
```

## Formatting

In Laravel Blade, money values are automatically formatted according to the current locale.

For example, in `en_US`, this may render as `€12.35`:

```blade
{{ Money::EUR('12.35') }}
```

Formatting uses PHP `NumberFormatter`, so the `intl` extension is required.

You can specify a custom locale:

```blade
{{ Money::EUR('12.34')->toHumanReadable(locale: 'cs_CZ') }}
```

For `cs_CZ`, this may render as `12,34 €`.

## Eloquent casts

Laravel Money provides casts for storing money values as decimal or cents.

Cast definitions are specified by:

- currency code source
  - fixed: defined by a three-letter uppercase currency code
  - dynamic: defined by a currency column name
- amount source
  - decimal: amount in major units
  - cents: amount in minor units

### Fixed currency decimal cast

Use this when the database column stores only the decimal amount and the currency is fixed:

```php
use Illuminate\Database\Eloquent\Model;
use JosefTraxler\LaravelMoney\Money;

class Product extends Model
{
    protected function casts(): array
    {
        return [
            'price' => Money::class . ':EUR',
        ];
    }
}
```

Usage:

```php
$product = Product::find(1);
$product->price->getDecimal(); // "19.99"
$product->price->getCurrency()->getCode(); // "EUR"
```

> ⚠️ When using a fixed currency cast, assigning a Money instance with a different currency throws MoneyMismatchCurrencyException.

### Dynamic currency column

Use this when the amount and currency are stored in separate columns:

```php
use Illuminate\Database\Eloquent\Model;
use JosefTraxler\LaravelMoney\Money;

class Order extends Model
{
    protected function casts(): array
    {
        return [
            'price' => Money::class . ':currency',
            'currency' => 'string',
        ];
    }
}
```

Usage:

```php
$order = new Order();
$order->price = Money::USD('19.99');
$order->price->getDecimal(); // "19.99"
$order->price->getCurrency()->getCode(); // "USD"
$order->currency; // "USD"
```

When using a dynamic currency column, the package stores the currency code automatically.

When assigning `null` to a money attribute using a dynamic currency column, only the amount column is set to `null`.
The currency column is preserved intentionally, because the same currency column may be shared by multiple money attributes.

### Cents storage

If you store money as cents, pass `cents` as the second-cast argument:

```php
use Illuminate\Database\Eloquent\Model;
use JosefTraxler\LaravelMoney\Money;

class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'amount_in_cents' => Money::class . ':currency,cents',
            'currency' => 'string',
        ];
    }
}
```

The example below stores `1999` in `amount_in_cents` and `"EUR"` in `currency`:

```php
$payment = new Payment();
$payment->amount_in_cents = Money::EUR('19.99');
$payment->save();
```

When retrieving the value, the package automatically converts cents to `Money` using `Money::fromCents()`.

## Design notes

### Strict currency codes

Currency codes are validated as three-letter ISO 4217-style codes. Whitespace is not trimmed automatically. This is intentional: invalid or unnormalized input should be handled before creating a currency value.

### Precision caching

Currency precision is resolved from configuration and cached per currency code. This keeps the package efficient when many money objects are created during a request.

### Decimal normalization

Money amounts are normalized according to currency precision:

```php
Money::EUR('10')->getDecimal(); // "10.00"
Money::JPY('10')->getDecimal(); // "10"
```

## Testing

Run the test suite:

```bash
composer test
```

Run static analysis:

```bash
composer stan
```

Check code style:

```bash
composer cs
```

Fix code style:

```bash
composer cs-fix
```

## Contributing

Contributions are welcome.

Before opening a pull request, please ensure:

- tests pass: `composer test`
- code style passes: `composer cs`
- static analysis passes: `composer stan`

## License

MIT License © [Josef Traxler](https://github.com/joseftraxler)

See the [LICENSE](LICENSE) file for details.

## Support

- **Issues:** [GitHub Issues](https://github.com/joseftraxler/laravel-money/issues)
- **Source:** [GitHub Repository](https://github.com/joseftraxler/laravel-money)
