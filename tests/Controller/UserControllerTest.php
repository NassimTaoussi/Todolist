<?php

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    

    private static function createFormData(
        string $username = 'Username',
        string $email = 'Email',
        string $password = 'Password',
        string $role = "Role"
    ): array {
        return [
            'parameters' => [
                'task' => [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                ],
            ],
        ];
    }
}