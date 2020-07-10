<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Manager\TaskManager;
use App\Security\TaskVoter;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController.
 */
class TaskController extends AbstractController
{
    /**
     * A TaskManager Instance.
     *
     * @var TaskManager
     */
    private $taskManager;

    /**
     * TaskController constructor.
     *
     * @param TaskManager $taskManager
     */
    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     * Show the Tasks list to do.
     *
     * @return Response
     *
     * @Route("/tasks/todo", name="task_list_todo", methods={"GET"})
     */
    public function tasksToDo(): Response
    {
        $tasks = $this->taskManager->findAllToDo();

        return $this->render(
            'task/list_todo.html.twig',
            ['tasks' => $tasks]
        );
    }

    /**
     * Show the Tasks list done.
     *
     * @return Response
     *
     * @Route("/tasks/done", name="task_list_done", methods={"GET"})
     */
    public function tasksDone(): Response
    {
        $tasks = $this->taskManager->findAllDone();

        return $this->render(
            'task/list_done.html.twig',
            ['tasks' => $tasks]
        );
    }

    /**
     * Add a new Task.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/tasks/create", name="task_create", methods={"GET", "POST"})
     */
    public function createTask(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskManager->createTask($task);

            $this->addFlash(
                'success',
                'La tâche a été bien été ajoutée.'
            );

            return $this->redirectToRoute('task_list_todo');
        }

        return $this->render(
            'task/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Update a Task.
     *
     * @param Task    $task
     * @param Request $request
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/tasks/{id}/edit", name="task_edit", methods={"GET", "POST"})
     */
    public function editTask(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskManager->updateTask($task);

            $this->addFlash(
                'success',
                'La tâche a bien été modifiée.'
            );

            return $this->redirectToRoute('task_list_todo');
        }

        return $this->render(
            'task/edit.html.twig',
            ['form' => $form->createView(), 'task' => $task]
        );
    }

    /**
     * Toggle a Task to do as Task done and vice versa.
     *
     * @param Task $task
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/tasks/{id}/toggle", name="task_toggle", methods={"GET"})
     */
    public function toggleTask(Task $task): Response
    {
        $this->taskManager->toggle($task);

        if (true === $task->isDone()) {
            $this->addFlash(
                'success',
                sprintf(
                    'La tâche %s a bien été marquée comme faite.',
                    $task->getTitle()
                )
            );

            return $this->redirectToRoute('task_list_todo');
        }

        $this->addFlash(
            'success',
            sprintf(
                'La tâche %s a bien été rebasculée dans les tâches à faire.',
                $task->getTitle()
            )
        );

        return $this->redirectToRoute('task_list_done');
    }

    /**
     * Delete a Task.
     *
     * @param Task $task
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/tasks/{id}/delete", name="task_delete", methods={"DELETE"})
     */
    public function deleteTask(Task $task): Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::DELETE, $task);

        $this->taskManager->deleteTask($task);

        $this->addFlash(
            'success',
            'La tâche a bien été supprimée.'
        );

        return $this->redirectToRoute('task_list_todo');
    }
}
