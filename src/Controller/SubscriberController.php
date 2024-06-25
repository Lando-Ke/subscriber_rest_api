<?php

namespace App\Controller;

use App\Library\ResponseHandler;
use App\Library\SubscriberValidator;
use App\Repository\SubscriberRepository;

class SubscriberController
{
    private SubscriberRepository $subscriberRepository;

    private ResponseHandler $responseHandler;

    public function __construct(
        SubscriberRepository $subscriberRepository,
        ResponseHandler $responseHandler
    ) {
        $this->subscriberRepository = $subscriberRepository;
        $this->responseHandler = $responseHandler;
    }

    /**
     * Get all subscribers
     *
     * @param [type] $request
     * @return array|null
     */
    public function index($request): ?array
    {
        $page = isset($request['page']) ? (int)$request['page'] : 1;
        $pageSize = isset($request['pageSize']) ? (int)$request['pageSize'] : 10;

        if ($this->subscriberRepository->isRateLimitExceeded('getAllSubscribers')) {
            $this->responseHandler->rateLimitExceededResponse();
        }

        $subscribers = $this->subscriberRepository->getPaginatedSubscribers($page, $pageSize);

        $responseData = [
            'page' => $page,
            'pageSize' => $pageSize,
            'subscribers' => $subscribers['items'],
            'total' => $subscribers['total'],
        ];

        return $this->responseHandler->sendJsonResponse(200, 'Subscribers retrieved successfully.', $responseData);
    }

    /**
     * Read/Fetch a subscriber
     *
     * @param [type] $request
     * @return array|null
     */
    public function read($request): ?array
    {
        $email = is_array($request) && isset($request['email']) ? $request['email'] : null;

        if (! $email) {
            return $this->responseHandler->invalidRequestResponse('email');
        }

        if ($this->subscriberRepository->isRateLimitExceeded($email)) {
            return $this->responseHandler->rateLimitExceededResponse();
        }

        $subscriber = $this->subscriberRepository->findSubscriberByEmail($email);
        $message = $subscriber? 'Subscriber retrieved successfully.' : 'Subscriber not found.';

        return $this->responseHandler->sendJsonResponse(200, $message, $subscriber);
    }

    /**
     * Create a new subscriber
     *
     * @param [type] $request
     * @return array|null
     */
    public function create($request): ?array
    {
        $requiredFields = ['email', 'name', 'last_name', 'status'];

        foreach ($requiredFields as $field) {
            if (!isset($request[$field])) {
                return $this->responseHandler->invalidRequestResponse($field);
            }
        }

        $validationErrors = SubscriberValidator::validateSubscriber($request);

        if (! empty($validationErrors)) {
            return $this->responseHandler->validationErrorResponse($validationErrors);
        }

        $email = $request['email'];

        if ($this->subscriberRepository->isRateLimitExceeded($email)) {
            return $this->responseHandler->rateLimitExceededResponse();
        }

        $subscriber = $this->subscriberRepository->findSubscriberByEmail($email);
        $message = $subscriber ? 'Subscriber already exists.' : 'Subscriber created successfully.';

        if (! $subscriber) {
            $this->subscriberRepository->addSubscriber($request);
            $subscriber = $this->subscriberRepository->findSubscriberByEmail($email);
        }

        return $this->responseHandler->sendJsonResponse(200, $message, $subscriber);
    }
}
