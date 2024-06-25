<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Model\BaseModel;
use App\Library\Database\Contracts\DatabaseConnectionInterface;
use PDOStatement;

class BaseModelTest extends TestCase
{
    protected $connectionMock;

    protected BaseModel $baseModel;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {

        $this->connectionMock = $this->createMock(DatabaseConnectionInterface::class);
        $this->baseModel = new BaseModel($this->connectionMock, 'test_table');
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFind()
    {

        $expectedReturn = ['id' => 1, 'name' => 'Test'];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn($expectedReturn);

        $this->connectionMock->expects($this->once())->method('query')->willReturn($statementMock);

        $result = $this->baseModel->find('id', 1);

        $this->assertEquals($expectedReturn, $result);
    }

    public function testStore()
    {
        $expectedReturn = 1;
        $data = ['name' => 'Test', 'email' => 'test@test.com'];

        $this->connectionMock->expects($this->once())->method('insert')->willReturn($expectedReturn);

        $result = $this->baseModel->store($data);

        $this->assertEquals($expectedReturn, $result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testPaginate()
    {
        $items = [['id' => 1, 'name' => 'Test']];
        $total = ['total' => 1];

        // Mock for the fetchAll call for getting paginated items
        $statementMockForItems = $this->createMock(PDOStatement::class);
        $statementMockForItems->method('fetchAll')->willReturn($items);

        // Mock for the fetch call for getting total count
        $statementMockForTotal = $this->createMock(PDOStatement::class);
        $statementMockForTotal->method('fetch')->willReturn($total);

        // Expect the query method to be called twice with different arguments and return different mocks
        $this->connectionMock->expects($this->exactly(2))->method('query')
            ->will($this->onConsecutiveCalls($statementMockForItems, $statementMockForTotal));

        $result = $this->baseModel->paginate(1, 10);

        // Assert the expected structure and values
        $expectedReturn = [
            'items' => $items,
            'total' => $total['total'],
        ];
        $this->assertEquals($expectedReturn, $result);
    }



}
