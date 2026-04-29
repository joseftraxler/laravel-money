# Laravel Money

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joseftraxler/laravel-money.svg?style=flat-square)](https://packagist.org/packages/joseftraxler/laravel-money)
[![Total Downloads](https://img.shields.io/packagist/dt/joseftraxler/laravel-money.svg?style=flat-square)](https://packagist.org/packages/joseftraxler/laravel-money)
![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=flat-square&logo=php)
![Laravel Version](https://img.shields.io/badge/Laravel-12%2B-FF2D20?style=flat-square&logo=laravel)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)
[![CI](https://github.com/joseftraxler/laravel-money/actions/workflows/CI.yml/badge.svg)](https://github.com/joseftraxler/laravel-money/actions/workflows/CI.yml)

A lightweight, zero-configuration Laravel package that provides simple and elegant working with Money (based on [moneyphp/money](https://github.com/moneyphp/money) values in Laravel
applications. Handle monetary values with precision using Eloquent casts that support both decimal and cents formats,
with flexible currency management.

## Features

- 💰 **Precise Money Handling**: Store and retrieve monetary values without floating-point precision issues
- 🔄 **Flexible Storage**: Support for both decimal and cents (integer) storage formats
- 🌍 **Currency Management**: Built-in currency support with flexible configuration options
- 🎯 **Type-Safe**: Full type safety with proper Money and Currency contracts
- ⚡ **Zero Configuration**: Works out of the box with sensible defaults
- 🏗️ **Laravel Integration**: Seamless integration with Eloquent models through custom casts

## Installation

You can install the package via composer:

```bash
composer require joseftraxler/laravel-money
```

## Usage

### Working with Money

```php
use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

$oneCent = new Money(1, Currency::of('USD'));
$oneDollar = new Money(100, Currency::of('USD'));

// prints $0.99
echo $oneDollar->subtract($oneCent)->toHtml();
```

### Casting Money to and from Eloquent Models

```php
use Illuminate\Database\Eloquent\Model;
use JosefTraxler\LaravelMoney\MoneyCast;

/**
 * @property Money $price
 * @property Money $paid
 * @property string $paid_currency
 */
class Product extends Model
{
    protected $casts = [
        // Casts the decimal price attribute to Money using the default currency
        'price' => MoneyCast::class . ':USD',
        // Casts the paid decimal attribute to Money using the specified currency attribute
        'paid' => MoneyCast::class . ':paid_currency',
        // Casts the paid_in_cents integer attribute to Money
        'paid_in_cents' => MoneyCast::class . ':paid_currency,cents',
        'paid_currency' => 'string',
    ];
}
```

## Contributing

Contributions are welcome! Please ensure:

- Code passes all tests: `composer test`
- Code standards are met: `composer cs`
- Static analysis passes: `composer stan`

---

## License

MIT License © [Josef Traxler](https://github.com/joseftraxler)

---

## Support

- **Issues:** [GitHub Issues](https://github.com/joseftraxler/laravel-money/issues)
- **Source:** [GitHub Repository](https://github.com/joseftraxler/laravel-money)
