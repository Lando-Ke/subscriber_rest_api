<?php

namespace App\Library;

class SubscriberValidator
{
    public static function validateSubscriber($data)
    {
        $errors = [];

        if (! self::validateEmail($data['email'])) {
            $errors['email'] = 'Invalid email format.';
        }

        if (! self::validateName($data['name'])) {
            $errors['name'] = 'Invalid name.';
        }

        if (isset($data['last_name'])) {
            if (! self::validateLastName($data['last_name'])) {
                $errors['last_name'] = 'Invalid last name.';
            }
        }

        if (isset($data['status'])) {
            if (! self::validateStatus($data['status'])) {
                $errors['status'] = 'Invalid status.';
            }
        }

        return $errors;
    }

    private static function validateEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private static function validateName(string $name)
    {
        return trim($name) !== '';
    }

    private static function validateLastName(string $lastName)
    {
        return trim($lastName) !== '';
    }

    private static function validateStatus(mixed $status)
    {
        $validStatuses = ['1', '0'];

        return in_array($status, $validStatuses, true);
    }
}
