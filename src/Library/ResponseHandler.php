<?php

namespace App\Library;

class ResponseHandler
{
    public function sendJsonResponse($statusCode, $message, $details = null)
    {
        $response = ['statusCode' => $statusCode, 'content' => ['message' => $message, 'details' => $details]];
        $this->send($response);

        return $response;
    }

    public function validationErrorResponse(array $errors)
    {
        $response = ['statusCode' => 400, 'content' => ['message' => 'Validation failed.', 'details' => $errors]];
        $this->send($response);

        return $response;
    }

    public function rateLimitExceededResponse()
    {
        $this->sendJsonResponse(429, 'Rate limit exceeded. Please try again later.');
    }

    public function invalidRequestResponse($field)
    {
        $this->sendJsonResponse(400, "Invalid request. {$field} is required.");
    }

    protected function send($response)
    {
        http_response_code($response['statusCode']);
        header('Content-Type: application/json');
        echo json_encode($response['content']);
        exit;
    }
}
