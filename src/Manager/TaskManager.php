<?php

namespace App\Manager;

use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class TaskManager implements TaskManagerInterface
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private TaskRepository $taskRepository
    ) {
    }

    public function add(Task $task): void {
        $task->setCreatedAt(new DateTimeImmutable());
        $task->setIsDone(false);
        $task->setAuthor($this->security->getUser());

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function update(Task $task): void {
        $this->entityManager->flush();
    }

    public function delete(Task $task): void {
        $this->taskRepository->deleteOneTaskById($task->getId());
    }

    public function toggle(Task $task): void {
        if ($task->isDone() == false) {
            $task->setIsDone(true);
        }
        else {
            $task->setIsDone(false);
        }
    }

}