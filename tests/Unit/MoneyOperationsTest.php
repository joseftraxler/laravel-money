<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

$usd = Currency::of('USD');

$usd1 = new Money(1, $usd);
$usd2 = new Money(2, $usd);
$usd3 = new Money(3, $usd);
$usd5 = new Money(5, $usd);
$usd6 = new Money(6, $usd);

it('adds two money amounts correctly')
    ->expect($usd2->add($usd3)->getDecimal())
    ->toBe("5.00")
;

it('subtracts two money amounts correctly')
    ->expect($usd6->subtract($usd2)->getDecimal())
    ->toBe("4.00")
;

it('multiplies money amount by a number correctly')
    ->expect($usd2->multiply(3)->getDecimal())
    ->toBe("6.00")
;

it('divides money amount by a number correctly')
    ->expect($usd6->divide(2)->getDecimal())
    ->toBe("3.00");

it('throws exception when divide by zero')
    ->expect(fn () => $usd6->divide(0))
    ->throws(\InvalidArgumentException::class)
;
