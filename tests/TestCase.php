<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\App;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public Stubs\FakeApplication $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = App::getFacadeRoot();
    }
}
