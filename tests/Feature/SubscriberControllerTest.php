<?php

namespace App\Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Controller\SubscriberController;
use App\Library\ResponseHandler;
use App\Repository\SubscriberRepository;

class SubscriberControllerTest extends TestCase
{
    protected $subscriberRepositoryMock;

    protected $responseHandlerMock;

    protected SubscriberController $subscriberController;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->subscriberRepositoryMock = $this->createMock(SubscriberRepository::class);
        $this->responseHandlerMock = $this->createMock(ResponseHandler::class);

        $this->subscriberController = new SubscriberController($this->subscriberRepositoryMock,
            $this->responseHandlerMock);
    }

    public function testIndex()
    {
        $request = ['page' => 1, 'pageSize' => 10];

        $paginatedData = [
            'subscribers' => [
                ['id' => 1, 'email' => 'subscriber1@example.com', 'name' => 'Subscriber 1'],
                ['id' => 2, 'email' => 'subscriber2@example.com', 'name' => 'Subscriber 2'],
            ],
            'page' => 1,
            'pageSize' => 10,
        ];

        $expectedResponse = [
            'status' => 200,
            'data' => 'Subscribers retrieved successfully.',
            'subscribers' => $paginatedData['subscribers'],
            'page' => $paginatedData['page'],
            'pageSize' => $paginatedData['pageSize'],
        ];

        $this->subscriberRepositoryMock->method('isRateLimitExceeded')->willReturn(false);
        $this->subscriberRepositoryMock->method('getPaginatedSubscribers')->willReturn($paginatedData);
        $this->responseHandlerMock->method('sendJsonResponse')->willReturn($expectedResponse);

        $response = $this->subscriberController->index($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testReadSuccess()
    {
        $request = ['email' => 'test@example.com'];
        $expectedSubscriber = ['id' => 1, 'email' => 'test@example.com', 'name' => 'Test User'];
        $expectedResponse = [
            'status' => 200,
            'data' => 'Subscriber retrieved successfully.',
            'subscriber' => $expectedSubscriber,
        ];

        $this->subscriberRepositoryMock->method('isRateLimitExceeded')->with('test@example.com')->willReturn(false);

        $this->subscriberRepositoryMock->method('findSubscriberByEmail')->with('test@example.com')->willReturn($expectedSubscriber);

        $this->responseHandlerMock->method('sendJsonResponse')->with(200, 'Subscriber retrieved successfully.',
            $expectedSubscriber)->willReturn($expectedResponse);

        $response = $this->subscriberController->read($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testReadRateLimitExceeded()
    {
        $request = ['email' => 'test@example.com'];

        $this->subscriberRepositoryMock->method('isRateLimitExceeded')->with('test@example.com')->willReturn(true);

        $this->responseHandlerMock->expects($this->once())->method('rateLimitExceededResponse')->willReturn([
            'status' => 429,
            'data' => 'Rate limit exceeded.',
        ]);

        $this->subscriberRepositoryMock->expects($this->never())->method('findSubscriberByEmail');

        $response = $this->subscriberController->read($request);
        $this->assertEquals(['status' => 429, 'data' => 'Rate limit exceeded.'], $response);
    }

    public function testReadInvalidRequest()
    {
        $request = [];

        $this->responseHandlerMock->expects($this->once())->method('invalidRequestResponse')->willReturn([
            'status' => 400,
            'data' => 'Invalid request.',
        ]);

        $this->subscriberRepositoryMock->expects($this->never())->method('isRateLimitExceeded');

        $this->subscriberRepositoryMock->expects($this->never())->method('findSubscriberByEmail');

        $response = $this->subscriberController->read($request);
        $this->assertEquals(['status' => 400, 'data' => 'Invalid request.'], $response);
    }

    public function testCreate_Successful()
    {
        $request = ['email' => 'new@example.com', 'name' => 'New User'];
        $expectedResponseJson = json_encode([
            'status' => 200,
            'message' => 'Subscriber created successfully.',
            'subscriber' => $request,
        ]);

        $this->subscriberRepositoryMock->method('isRateLimitExceeded')->willReturn(false);
        $this->subscriberRepositoryMock->method('findSubscriberByEmail')->willReturnOnConsecutiveCalls(null, $request);
        $this->subscriberRepositoryMock->method('addSubscriber')->willReturn(1);

        // Ensure that sendJsonResponse is mocked to return a JSON string
        $this->responseHandlerMock->method('sendJsonResponse')
            ->willReturnCallback(function($status, $message, $data) {
                return json_encode([
                    'status' => $status,
                    'message' => $message,
                    'subscriber' => $data
                ]);
            });

        $response = $this->subscriberController->create($request);

        // Check if response is not null before assertion to avoid type error
        $this->assertNotNull($response, "Expected JSON response, got null");
        $this->assertJsonStringEqualsJsonString($expectedResponseJson, $response);
    }

    public function testCreate_WithValidationErrors()
    {
        $request = ['email' => 'newexamplecom', 'name' => ''];
        $validationErrors = ['email' => 'Invalid email format.', 'name' => 'Invalid name.'];
        $expectedResponseJson = json_encode([
            'status' => 400,
            'errors' => $validationErrors,
        ]);

        $this->subscriberRepositoryMock->method('isRateLimitExceeded')->willReturn(false);
        $this->responseHandlerMock->method('validationErrorResponse')->willReturn($expectedResponseJson);

        $response = $this->subscriberController->create($request);
        $this->assertJsonStringEqualsJsonString($expectedResponseJson, $response);
    }

    public function testCreate_RateLimitExceeded()
    {
        $request = ['email' => 'test@example.com'];
        $rateLimitResponseJson = json_encode([
            'status' => 429,
            'message' => 'Rate limit exceeded.'
        ]);

        $this->subscriberRepositoryMock->method('isRateLimitExceeded')->with('test@example.com')->willReturn(true);
        $this->responseHandlerMock->method('rateLimitExceededResponse')->willReturn($rateLimitResponseJson);

        // Ensure that findSubscriberByEmail is never called.
        $this->subscriberRepositoryMock->expects($this->never())->method('findSubscriberByEmail');

        $response = $this->subscriberController->create($request);

        // Check if response is not null before assertion
        if ($response !== null) {
            $this->assertJsonStringEqualsJsonString($rateLimitResponseJson, $response);
        } else {
            // Fail the test if the response is null
            $this->fail("Expected JSON response, got null");
        }
    }

    public function testCreate_InvalidRequest()
    {
        $request = ['name' => 'Name Without Email'];
        $invalidRequestResponse = ['status' => 400, 'message' => 'Invalid request.'];

        $this->responseHandlerMock->method('invalidRequestResponse')->willReturn($invalidRequestResponse);

        $response = $this->subscriberController->create($request);
        $this->assertEquals($invalidRequestResponse, $response);
    }

    public function testCreate_SubscriberExists()
    {
        $request = ['email' => 'existing@example.com', 'name' => 'Existing User'];
        $existingSubscriber = ['id' => 1, 'email' => 'existing@example.com', 'name' => 'Existing User'];
        $subscriberExistsResponseJson = json_encode([
            'status' => 200,
            'message' => 'Subscriber already exists.',
            'subscriber' => $existingSubscriber
        ]);

        $this->subscriberRepositoryMock->method('isRateLimitExceeded')->willReturn(false);
        $this->subscriberRepositoryMock->method('findSubscriberByEmail')->willReturn($existingSubscriber);
        $this->responseHandlerMock->method('sendJsonResponse')->willReturn($subscriberExistsResponseJson);

        $response = $this->subscriberController->create($request);
        $this->assertJsonStringEqualsJsonString($subscriberExistsResponseJson, $response);
    }
}
