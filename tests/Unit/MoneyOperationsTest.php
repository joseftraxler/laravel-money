<?php

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

$usd = Currency::of('USD');

$usd1 = new Money(100, $usd);
$usd2 = new Money(200, $usd);
$usd3 = new Money(300, $usd);
$usd5 = new Money(500, $usd);
$usd6 = new Money(600, $usd);

it('adds two money amounts correctly')
    ->expect($usd2->add($usd3)->getAmount())
    ->toBe("500")
;

it('subtracts two money amounts correctly')
    ->expect($usd6->subtract($usd2)->getAmount())
    ->toBe("400")
;

it('multiplies money amount by a number correctly')
    ->expect($usd2->multiply(3)->getAmount())
    ->toBe("600")
;

it('divides money amount by a number correctly')
    ->expect($usd6->divide(2)->getAmount())
    ->toBe("300");

it('throws exception when divide by zero')
    ->expect(fn() => $usd6->divide(0))
    ->throws(\InvalidArgumentException::class)
;
