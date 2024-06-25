<?php

namespace App\Library\Cache\Contracts;

interface CacheMethodInterface
{
    public function get(string $key);
    public function set(string $key, $value, int $ttl = 3600);
    public function delete(string $key);
    public function clear();
}
