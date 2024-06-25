<?php

namespace App\Library\Cache\Methods;

use Predis\Client as PredisClient;
use App\Library\Cache\Contracts\CacheMethodInterface;

class RedisCache implements CacheMethodInterface
{
    protected PredisClient $client;

    public function __construct()
    {
        $this->client = new PredisClient([
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
        ]);
    }

    public function get(string $key)
    {
        $value = $this->client->get($key);
        return $value !== null ? unserialize($value) : null;
    }

    public function set(string $key, $value, int $ttl = 3600)
    {
        $serializedValue = serialize($value);
        $this->client->setex($key, $ttl, $serializedValue);
    }

    public function delete(string $key)
    {
        $this->client->del($key);
    }

    public function clear()
    {
        $this->client->flushdb();
    }
}