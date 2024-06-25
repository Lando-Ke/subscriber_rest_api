<?php

namespace App\Repository;

use App\Model\Subscriber;
use App\Library\Cache\Cache;
use App\Library\Database\Contracts\DatabaseConnectionInterface;
use App\Library\RateLimiting;

class SubscriberRepository
{
    private Cache $cache;

    private Subscriber $subscriber;

    private RateLimiting $rateLimiter;

    public function __construct(DatabaseConnectionInterface $db_connection)
    {
        $this->cache = new Cache();
        $this->subscriber = new Subscriber($db_connection);
        $this->rateLimiter = new RateLimiting($this->cache);
    }

    public function getPaginatedSubscribers(int $page, int $pageSize): bool|array
    {
        $cacheKey = "subscribers_page_{$page}_size_{$pageSize}";
        $subscribers = $this->cache->get($cacheKey);

        if (! $subscribers) {
            $subscribers = $this->subscriber->getPaginatedSubscribers($page, $pageSize);
            $this->cache->set($cacheKey, $subscribers);
        }

        return $subscribers;
    }

    public function findSubscriberByEmail(string $email): bool|array
    {
        $cacheKey = 'subscriber_'.$email;
        $subscriber = $this->cache->get($cacheKey);

        if (! $subscriber) {
            $subscriber = $this->subscriber->findByEmail($email);
            $this->cache->set($cacheKey, $subscriber, 3600);
        }

        return $subscriber;
    }

    public function addSubscriber(array $subscriberData)
    {
        $this->subscriber->addSubscriber($subscriberData);
    }

    public function isRateLimitExceeded($key): bool
    {
        return $this->rateLimiter->isRateLimitExceeded($key);
    }

    public function setCache(Cache $cache): void
    {
        $this->cache = $cache;
    }

    public function setSubscriber(Subscriber $subscriber): void
    {
        $this->subscriber = $subscriber;
    }

    public function setRateLimiter(RateLimiting $rateLimiter): void
    {
        $this->rateLimiter = $rateLimiter;
    }
}
