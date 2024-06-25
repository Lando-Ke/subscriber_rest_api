<?php

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Repository\SubscriberRepository;
use App\Library\Database\Methods\MySqlDatabase;
use App\Library\Cache\Cache;
use App\Library\RateLimiting;
use App\Model\Subscriber;

class SubscriberWorkflowTest extends TestCase
{
    private SubscriberRepository $subscriberRepository;

    protected function setUp(): void
    {
        $dbConnection = new MySqlDatabase();
        $cache = new Cache();
        $rateLimiter = new RateLimiting($cache);
        $subscriberModel = new Subscriber($dbConnection);

        $this->subscriberRepository = new SubscriberRepository($dbConnection);
        $this->subscriberRepository->setCache($cache);
        $this->subscriberRepository->setSubscriber($subscriberModel);
        $this->subscriberRepository->setRateLimiter($rateLimiter);
    }

    public function testSubscriberWorkflow()
    {
        $subscriberData = ['email' => 'test@example.com', 'name' => 'Test User'];
        $this->subscriberRepository->addSubscriber($subscriberData);

        $retrievedSubscriber = $this->subscriberRepository->findSubscriberByEmail('test@example.com');
        $this->assertEquals($subscriberData['email'], $retrievedSubscriber['email']);
        $this->assertEquals($subscriberData['name'], $retrievedSubscriber['name']);

        $isRateLimited = $this->subscriberRepository->isRateLimitExceeded('test@example.com');
        $this->assertFalse($isRateLimited);

        $retrievedSubscriberFromCache = $this->subscriberRepository->findSubscriberByEmail('test@example.com');
        $this->assertEquals($retrievedSubscriber, $retrievedSubscriberFromCache);
    }

    protected function tearDown(): void
    {
        // Clean up database and cache if necessary
    }
}
