<?php

declare(strict_types=1);

namespace Tests\Stubs;

use Illuminate\Contracts\Config\Repository;

final class FakeConfig implements Repository
{
    /** @var array<string,mixed> */
    private array $values = [];

    public function has($key)
    {
        return isset($this->values[$key]);
    }

    /**
     * @param string $key
     * @param mixed $default
     */
    public function get($key, $default = null)
    {
        return $this->values[$key] ?? $default;
    }

    /** @return array<string,mixed> */
    public function all()
    {
        return $this->values;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
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
