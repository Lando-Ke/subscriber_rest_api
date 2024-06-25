<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Model\Subscriber;
use App\Library\Database\Contracts\DatabaseConnectionInterface;
use PDOStatement;

class SubscriberTest extends TestCase
{
    protected $connectionMock;

    protected Subscriber $subscriber;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(DatabaseConnectionInterface::class);
        $this->subscriber = new Subscriber($this->connectionMock);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindByEmail()
    {
        $expectedReturn = ['id' => 1, 'email' => 'test@test.com'];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn($expectedReturn);

        $this->connectionMock->expects($this->once())->method('query')->willReturn($statementMock);

        $result = $this->subscriber->findByEmail('test@test.com');

        $this->assertEquals($expectedReturn, $result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetNameById()
    {
        $expectedReturn = ['id' => 1, 'name' => 'Test Name'];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn($expectedReturn);

        $this->connectionMock->expects($this->once())->method('query')->willReturn($statementMock);

        $result = $this->subscriber->getNameById(1);

        $this->assertEquals($expectedReturn['name'], $result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetPaginatedSubscribers()
    {
        $paginatedItems = [
            ['id' => 1, 'name' => 'Test 1', 'email' => 'test1@test.com'],
            ['id' => 2, 'name' => 'Test 2', 'email' => 'test2@test.com'],
        ];
        $paginatedItemsStatementMock = $this->createMock(PDOStatement::class);
        $paginatedItemsStatementMock->method('fetchAll')->willReturn($paginatedItems);

        $totalCount = ['total' => 100];
        $totalCountStatementMock = $this->createMock(PDOStatement::class);
        $totalCountStatementMock->method('fetch')->willReturn($totalCount);

        $this->connectionMock->expects($this->exactly(2))->method('query')->willReturnOnConsecutiveCalls($paginatedItemsStatementMock,
                $totalCountStatementMock);

        $result = $this->subscriber->getPaginatedSubscribers(1, 10);

        $expectedReturn = [
            'items' => $paginatedItems,
            'total' => $totalCount['total'],
        ];
        $this->assertEquals($expectedReturn, $result);
    }

    public function testAddSubscriber()
    {
        $expectedReturn = 1;
        $data = ['name' => 'Test', 'email' => 'test@test.com'];

        $this->connectionMock->expects($this->once())->method('insert')->willReturn($expectedReturn);

        $result = $this->subscriber->addSubscriber($data);

        $this->assertEquals($expectedReturn, $result);
    }
}
