<?php

declare(strict_types=1);

namespace Tests\Stubs;

use Illuminate\Contracts\Config\Repository;

final class FakeConfig implements Repository
{
    private array $values = [];

    public function has($key)
    {
        return isset($this->values[$key]);
    }

    public function get($key, $default = null)
    {
        return $this->values[$key] ?? $default;
    }

    public function all()
    {
        return $this->values;
    }

    public function set($key, $value = null)
    {
        $this->values[$key] = $value;
    }

    public function prepend($key, $value)
    {
        // Unnecessary for tests
    }

    public function push($key, $value)
    {
        // Unnecessary for tests
    }
}
