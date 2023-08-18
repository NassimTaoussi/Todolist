<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testTitleTask()
    {
        $task = new Task();
        $title = "Test Title 1";
        
        $task->setTitle($title);
        $this->assertEquals("Test Title 1", $task->getTitle());
    }

    public function testContentTask()
    {
        $task = new Task();
        $content = "Test Content 2";
        
        $task->setContent($content);
        $this->assertEquals("Test Content 2", $task->getContent());
    }

    public function testCreatedAtTask()
    {
        $task = new Task();
        $format = 'Y-m-d H:i:s';
        $createdAt = DateTimeImmutable::createFromFormat($format, "2023-08-11 23:00:00");
        
        $task->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $task->getCreatedAt());
    }

    public function testIsDoneTask()
    {
        $task = new Task();
        $isDone = true;

        $task->setIsDone($isDone);
        $this->assertEquals(true, $task->isDone());
    }

    public function testAuthorTask()
    {
        $task = new Task();
        $user = new User();
        $task->setAuthor($user);
        $this->assertEquals($user, $task->getAuthor());
    }
}