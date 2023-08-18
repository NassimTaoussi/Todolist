<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Manager\TaskManager;
use App\Manager\TaskManagerInterface;
use App\Repository\TaskRepository;
use App\Security\Voter\TaskVoter;
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
    public function createTask(Request $request, TaskManagerInterface $taskManagerInterface): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskManagerInterface->add($task);
            $this->addFlash('success', 'Vous venez de créé un nouvelle tâche');

            return $this->redirectToRoute('tasks');
        }


        return $this->render('task/create_task.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/tasks/edit/{id}', name: 'edit_task')]
    #[IsGranted(TaskVoter::TASK_EDIT, subject: 'task')]
    public function editTask(Task $task, Request $request, TaskManagerInterface $taskManagerInterface): Response
    {
        //$this->denyAccessUnlessGranted(TaskVoter::TASK_EDIT, $task);
        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskManagerInterface->update($task);
            $this->addFlash('success', 'Modification appliquer');

            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/edit_task.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    #[Route('/tasks/delete/{id}', name: 'delete_task')]
    #[IsGranted(TaskVoter::TASK_DELETE, subject: 'task')]
    public function deleteTask(Task $task, TaskManagerInterface $taskManagerInterface): Response
    {
        //$this->denyAccessUnlessGranted(TaskVoter::TASK_DELETE, $task);
        $taskManagerInterface->delete($task);
        return $this->redirectToRoute('tasks');

    }

    #[Route('/tasks/{id}', name: 'show_task')]
    #[IsGranted("ROLE_USER")]
    public function showTask(Task $task): Response
    {
        return $this->render('task/show_task.html.twig', [
            'task' => $task
        ]);
    }

    #[Route('/tasks/toggle/{id}', name: 'task_toggle')]
    #[IsGranted(TaskVoter::TASK_TOGGLE, subject: 'task')]
    public function toggleTask(Task $task, TaskManagerInterface $taskManagerInterface)
    {
        //$this->denyAccessUnlessGranted(TaskVoter::TASK_TOGGLE, $task) ;
        $taskManagerInterface->toggle($task);
        return $this->redirectToRoute('tasks');
    }
}
