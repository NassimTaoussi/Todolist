<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Manager\TaskManager;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'tasks')]
    #[IsGranted("ROLE_USER")]

    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/create', name: 'create_task')]
    #[IsGranted("ROLE_USER")]
    public function createTask(Request $request, TaskManager $taskManager): Response
    {
        $user = $this->getUser();

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskManager->add($task, $user);
            $this->addFlash('success', 'Vous venez de créé un nouvelle tâche');

            return $this->redirectToRoute('tasks');
        }


        return $this->render('task/create_task.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/tasks/edit/{id}', name: 'edit_task')]
    #[IsGranted("ROLE_USER")]
    public function editTask(int $id, TaskRepository $taskRepository, Request $request, TaskManager $taskManager): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskManager->update($task);
            $this->addFlash('success', 'Modification appliquer');

            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/edit_task.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    #[Route('/tasks/delete/{id}', name: 'delete_task')]
    #[IsGranted("ROLE_USER")]
    public function deleteTask(int $id, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);

        if ($task->getAuthor() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer cette tâche.');
            return $this->redirectToRoute('tasks');
        }

        $taskRepository->deleteOneTaskById($id);
        $this->addFlash('success', 'La tâche a bien été supprimée.');
        return $this->redirectToRoute('tasks');

    }

    #[Route('/tasks/{id}', name: 'show_task')]
    #[IsGranted("ROLE_USER")]
    public function showTask(int $id, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);
        
        return $this->render('task/show_task.html.twig', [
            'task' => $task
        ]);
    }
}
