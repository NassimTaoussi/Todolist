<?php

namespace App\DataFixtures;

use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($count = 0; $count < 20; ++$count) {
            $task = new Task();
            $task->setTitle('Task ' . $count);
            $task->setContent('Quamquam cetero alios enim enim studiis artibus qui artibus ipse.');
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setIsDone(false);
            $task->setAuthor(null);
            $manager->persist($task);
        }

        $manager->flush();
    }

}