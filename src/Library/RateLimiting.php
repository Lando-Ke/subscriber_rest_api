<?php

namespace App\Library;

use App\Library\Cache\Contracts\CacheInterface;

class RateLimiting
{
    protected CacheInterface $cache;

    protected mixed $rateLimit;

    protected mixed $rateLimitDuration;

    public function __construct(CacheInterface $cache, $rateLimit = 1000, $rateLimitDuration = 3600)
    {
        $this->cache = $cache;
        $this->rateLimit = $rateLimit;
        $this->rateLimitDuration = $rateLimitDuration;
    }

    public function isRateLimitExceeded($identifier): bool
    {
        $key = $this->getRateLimitKey($identifier);
        $currentCount = $this->cache->get($key) ?: 0;

        if ($currentCount >= $this->rateLimit) {
            return true;
        }

        $this->cache->set($key, $currentCount + 1, $this->rateLimitDuration);

        return false;
    }

    private function getRateLimitKey($identifier): string
    {
        return 'ratelimit:'.$identifier;
    }
}
