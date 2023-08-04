<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testUsername()
    {
        $user = new User();
        $username = "Test Username 1";
        
        $user->setUsername($username);
        $this->assertEquals("Test Username 1", $user->getUsername());
    }

}