<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testUsernameUser()
    {
        $user = new User();
        $username = "Test Username 1";
        
        $user->setUsername($username);
        $this->assertEquals("Test Username 1", $user->getUsername());
    }

    public function testEmailUser()
    {
        $user = new User();
        $email = "emailTestUser@email.com";
        
        $user->setEmail($email);
        $this->assertEquals("emailTestUser@email.com", $user->getEmail());
    }

    public function testPasswordUser()
    {
        $user = new User();
        $password = "password";
        
        $user->setPassword($password);
        $this->assertEquals("password", $user->getPassword());
    }

    public function testRoleUser()
    {
        $user = new User();
        $role = 'ROLE_ADMIN';
        
        $user->setRole($role);
        $this->assertEquals('ROLE_ADMIN', $user->getRole());
    }

}