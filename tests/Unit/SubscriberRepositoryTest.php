<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Repository\SubscriberRepository;
use App\Model\Subscriber;
use App\Library\Cache\Cache;
use App\Library\Database\Contracts\DatabaseConnectionInterface;
use App\Library\RateLimiting;

class SubscriberRepositoryTest extends TestCase
{
    private $cacheMock;

    private $subscriberMock;

    private $rateLimiterMock;

    private SubscriberRepository $subscriberRepository;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {

        $this->cacheMock = $this->createMock(Cache::class);
        $this->subscriberMock = $this->createMock(Subscriber::class);
        $this->rateLimiterMock = $this->createMock(RateLimiting::class);
        $dbConnectionMock = $this->createMock(DatabaseConnectionInterface::class);

        $this->subscriberRepository = new SubscriberRepository($dbConnectionMock);

        $this->subscriberRepository->setCache($this->cacheMock);
        $this->subscriberRepository->setSubscriber($this->subscriberMock);
        $this->subscriberRepository->setRateLimiter($this->rateLimiterMock);
    }

    public function testGetPaginatedSubscribers()
    {
        $page = 1;
        $pageSize = 10;
        $cacheKey = "subscribers_page_{$page}_size_{$pageSize}";

        $expectedResult = [
            ['id' => 1, 'email' => 'test1@example.com', 'name' => 'Test User 1'],
            ['id' => 2, 'email' => 'test2@example.com', 'name' => 'Test User 2'],

        ];

        $this->cacheMock->expects($this->once())->method('get')->with($cacheKey)->willReturn(false);

        $this->subscriberMock->expects($this->once())->method('getPaginatedSubscribers')->with($page,
            $pageSize)->willReturn($expectedResult);

        $this->cacheMock->expects($this->once())->method('set')->with($cacheKey, $expectedResult);

        $result = $this->subscriberRepository->getPaginatedSubscribers($page, $pageSize);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFindSubscriberByEmail()
    {
        $email = 'test@example.com';
        $cacheKey = 'subscriber_'.$email;

        $expectedSubscriber = ['id' => 1, 'email' => $email, 'name' => 'Test User'];

        $this->cacheMock->expects($this->once())->method('get')->with($cacheKey)->willReturnOnConsecutiveCalls(false,
                $expectedSubscriber);

        $this->subscriberMock->expects($this->once())->method('findByEmail')->with($email)->willReturn($expectedSubscriber);

        $this->cacheMock->expects($this->once())->method('set')->with($cacheKey, $expectedSubscriber);

        $result = $this->subscriberRepository->findSubscriberByEmail($email);

        $this->assertEquals($expectedSubscriber, $result);
    }

    public function testIsRateLimitExceeded()
    {
        $key = 'test@example.com';
        $expectedResult = true;

        $this->rateLimiterMock->expects($this->once())->method('isRateLimitExceeded')->with($key)->willReturn($expectedResult);

        $result = $this->subscriberRepository->isRateLimitExceeded($key);

        $this->assertEquals($expectedResult, $result);
    }

    public function testAddSubscriber()
    {
        $subscriberData = ['email' => 'new@example.com', 'name' => 'New User'];

        $this->subscriberMock->expects($this->once())->method('addSubscriber')->with($subscriberData);

        $this->subscriberRepository->addSubscriber($subscriberData);
    }
}
