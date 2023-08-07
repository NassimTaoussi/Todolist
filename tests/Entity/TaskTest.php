<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTitle()
    {
        $task = new Task();
        $title = "Test Title 1";
        
        $task->setTitle($title);
        $this->assertEquals("Test Title 1", $task->getTitle());
    }

    public function testContent()
    {
        $task = new Task();
        $content = "Test Content 2";
        
        $task->setContent($content);
        $this->assertEquals("Test Content 2", $task->getContent());
    }
}