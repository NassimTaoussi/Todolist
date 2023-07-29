<?php

namespace App\Manager;

use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class TaskManager
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function add($task, $user) {
        //dump($task);
        //exit();

        $task->setCreatedAt(new DateTimeImmutable());
        $task->setIsDone(false);
        $task->setAuthor($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function update($task) {
        $this->entityManager->flush();
    }

}