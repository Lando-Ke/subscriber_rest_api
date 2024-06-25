<?php

namespace App\Library\Cache;

use App\Library\Cache\Methods\RedisCache;
use App\Library\Cache\Contracts\CacheInterface;
use App\Library\Cache\Contracts\CacheMethodInterface;

class Cache implements CacheInterface
{
    protected CacheMethodInterface $method;

    public function __construct(?CacheMethodInterface $method = null)
    {
        $this->uses($method);
    }

    /**
     * Set the cache method to use.
     */
    public function uses(?CacheMethodInterface $method = null)
    {
        $this->method = $method ?? $this->defaultMethod();

        return $this;
    }

    public function method(): CacheMethodInterface
    {
        return $this->method ?? $this->defaultMethod();
    }

    protected function defaultMethod(): CacheMethodInterface
    {
        return new RedisCache();
    }

    public function get(string $key)
    {
        return $this->method()->get($key);
    }

    public function set(string $key, $value, int $ttl = 300)
    {
        return $this->method()->set($key, $value, $ttl);
    }

    public function delete(string $key)
    {
        return $this->method()->delete($key);
    }

    public function clear()
    {
        return $this->method()->clear();
    }
}
