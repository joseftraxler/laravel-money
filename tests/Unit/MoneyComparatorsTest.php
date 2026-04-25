<?php

declare(strict_types=1);

use JosefTraxler\LaravelMoney\Currency;
use JosefTraxler\LaravelMoney\Money;

$eur = Currency::of('EUR');

$eur1 = new Money(100, $eur);
$Eur1b = new Money(100, $eur);
$eur2 = new Money(200, $eur);

it('returns true when comparing equal money amounts with equals()')
    ->assertTrue($eur1->equals($Eur1b));

it('returns true when comparing equal money amounts with greaterThanOrEqual()')
    ->assertTrue($eur1->greaterThanOrEqual($Eur1b));

it('returns true when comparing equal money amounts with lessThanOrEqual()')
    ->assertTrue($eur1->lessThanOrEqual($Eur1b));

it('returns true when comparing greater money amount with greaterThan()')
    ->assertTrue($eur2->greaterThan($eur1));

it('returns true when comparing greater money amount with greaterThanOrEqual()')
    ->assertTrue($eur2->greaterThanOrEqual($eur1));

it('returns true when comparing lesser money amount with lessThan()')
    ->assertTrue($eur1->lessThan($eur2));

it('returns true when comparing lesser money amount with lessThanOrEqual()')
    ->assertTrue($eur1->lessThanOrEqual($eur2))
;
