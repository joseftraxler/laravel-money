<?php

declare(strict_types=1);

namespace Tests\Stubs;

use ArrayAccess;
use Illuminate\Contracts\Foundation\Application;

/**
 * @implements ArrayAccess<string,mixed>
 */
final class FakeApplication implements Application, ArrayAccess
{
    /** @var array<string,mixed> */
    private array $bindings = [];

    /** @var array<string,mixed> */
    private array $instances = [];

    private string $locale = 'en_US';

    public function __construct()
    {
        $this->instances['app'] = $this;
        $this->instances[Application::class] = $this;
        $this->instances[\Illuminate\Contracts\Container\Container::class] = $this;

        $config = new FakeConfig();
        $this->instances['config'] = $config;
        $this->instances[\Illuminate\Contracts\Config\Repository::class] = $config;
    }

    public function version()
    {
        return 'test';
    }

    public function basePath($path = '')
    {
        return '';
    }

    public function bootstrapPath($path = '')
    {
        return '';
    }

    public function configPath($path = '')
    {
        return '';
    }

    public function databasePath($path = '')
    {
        return '';
    }

    public function langPath($path = '')
    {
        return '';
    }

    public function publicPath($path = '')
    {
        return '';
    }

    public function resourcePath($path = '')
    {
        return '';
    }

    public function storagePath($path = '')
    {
        return '';
    }

    /**
     * @param list<string> ...$environments
     */
    public function environment(...$environments)
    {
        return count($environments) === 0 ? 'testing' : in_array('testing', $environments, true);
    }

    public function runningInConsole()
    {
        return true;
    }

    public function runningUnitTests()
    {
        return true;
    }

    public function hasDebugModeEnabled()
    {
        return true;
    }

    public function maintenanceMode()
    {
        throw new \RuntimeException('Maintenance mode is disabled.');
    }

    public function isDownForMaintenance()
    {
        return false;
    }

    public function registerConfiguredProviders()
    {
    }

    public function register($provider, $force = false)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    public function registerDeferredProvider($provider, $service = null)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    public function resolveProvider($provider)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    public function boot()
    {
    }

    public function booting($callback)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    public function booted($callback)
    {
        // Unnecessary for tests
    }

    /**
     * @param array<array-key,mixed> $bootstrappers
     */
    public function bootstrapWith(array $bootstrappers)
    {
        // Unnecessary for tests
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getNamespace()
    {
        return '';
    }

    /**
     * @return array<string,mixed>
     */
    public function getProviders($provider)
    {
        return [];
    }

    public function hasBeenBootstrapped()
    {
        return true;
    }

    public function loadDeferredProviders()
    {
        // Unnecessary for tests
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function shouldSkipMiddleware()
    {
        return false;
    }

    public function terminating($callback)
    {
        return $this;
    }

    public function terminate()
    {
        // Unnecessary for tests
    }

    public function get(string $id)
    {
        return $this->make($id);
    }

    public function bound($abstract)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    public function alias($abstract, $alias)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    /**
     * @param array<mixed> $abstracts
     * @param array<mixed> $tags
     */
    public function tag($abstracts, $tags)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    /**
     * @return array<mixed>
     */
    public function tagged($tag)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    /**
     * @param string $abstract
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * @param array<string>|string $method
     */
    public function bindMethod($method, $callback)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    /**
     * @param string $abstract
     */
    public function bindIf($abstract, $concrete = null, $shared = false)
    {
        if (! isset($this->bindings[$abstract])) {
            $this->bind($abstract, $concrete, $shared);
        }
    }

    /**
     * @param string $abstract
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * @param string $abstract
     */
    public function singletonIf($abstract, $concrete = null)
    {
        if (! isset($this->bindings[$abstract])) {
            $this->singleton($abstract, $concrete);
        }
    }

    public function scoped($abstract, $concrete = null)
    {
        // Unnecessary for tests
    }

    public function scopedIf($abstract, $concrete = null)
    {
        // Unnecessary for tests
    }

    public function extend($abstract, \Closure $closure)
    {
        // Unnecessary for tests
    }

    /**
     * @param string $abstract
     */
    public function instance($abstract, $instance)
    {
        return $this->instances[$abstract] = $instance;
    }

    public function addContextualBinding($concrete, $abstract, $implementation)
    {
        // Unnecessary for tests
    }

    /**
     * @param array<string>|string $concrete
     * @return mixed
     */
    public function when($concrete)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    public function factory($abstract)
    {
        throw new \RuntimeException('Not implemented yet');
    }

    public function flush()
    {
        // Unnecessary for tests
    }

    /**
     * @param array<string,mixed> $parameters
     */
    public function make($abstract, array $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract]
            ?? throw new \RuntimeException("Failed to resolve {$abstract}");

        if ($concrete instanceof \Closure) {
            return $concrete($this, $parameters);
        }

        if (is_string($concrete)) {
            return $this->make($concrete, $parameters);
        }

        throw new \RuntimeException("Failed to resolve {$abstract}");
    }

    /**
     * @param array<string,mixed> $parameters
     */
    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
        // Unnecessary for tests
    }

    public function resolved($abstract)
    {
        return $this->has($abstract);
    }

    public function beforeResolving($abstract, ?\Closure $callback = null)
    {
        // Unnecessary for tests
    }

    public function resolving($abstract, ?\Closure $callback = null)
    {
        // Unnecessary for tests
    }

    public function afterResolving($abstract, ?\Closure $callback = null)
    {
        // Unnecessary for tests
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->make($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            throw new \RuntimeException('Cannot set a null key value.');
        }
        $this->instance($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->bindings[$offset]);
    }
}
