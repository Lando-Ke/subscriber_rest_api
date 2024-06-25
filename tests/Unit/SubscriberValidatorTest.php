<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Library\SubscriberValidator;

class SubscriberValidatorTest extends TestCase
{
    public function testValidateSubscriber()
    {
        $validData = [
            'email' => 'test@example.com',
            'name' => 'John',
            'last_name' => 'Doe',
            'status' => '1'
        ];
        $this->assertEmpty(
            SubscriberValidator::validateSubscriber($validData),
            'Valid data should return an empty array'
        );

        $invalidData = [
            'email' => 'invalid-email',
            'name' => '',
            'last_name' => '',
            'status' => 'invalid'
        ];
        $errors = SubscriberValidator::validateSubscriber($invalidData);
        $this->assertArrayHasKey('email', $errors, 'Invalid email should return an error');
        $this->assertArrayHasKey('name', $errors, 'Invalid name should return an error');
        $this->assertArrayHasKey('last_name', $errors, 'Invalid last name should return an error');
        $this->assertArrayHasKey('status', $errors, 'Invalid status should return an error');
    }
}
