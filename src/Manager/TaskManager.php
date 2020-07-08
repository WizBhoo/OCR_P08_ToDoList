<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Manager;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Security;

/**
 * Class TaskManager.
 */
class TaskManager
{
    /**
     * A TaskRepository Instance.
     *
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * A UserRepository Instance
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * A Security service Instance.
     *
     * @var Security
     */
    private $security;

    /**
     * TaskManager constructor.
     *
     * @param TaskRepository $taskRepository
     * @param UserRepository $userRepository
     * @param Security       $security
     */
    public function __construct(TaskRepository $taskRepository, UserRepository $userRepository, Security $security)
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    /**
     * Retrieve all tasks to do from db.
     *
     * @return Task[]
     */
    public function findAllToDo(): array
    {
        return $this->taskRepository->findBy(
            ['isDone' => false]
        );
    }

    /**
     * Retrieve all tasks done from db.
     *
     * @return Task[]
     */
    public function findAllDone(): array
    {
        return $this->taskRepository->findBy(
            ['isDone' => true]
        );
    }

    /**
     * Create a new Task with current User as author in db.
     *
     * @param Task $task
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createTask(Task $task): void
    {
        $user = $this->security->getUser()->getUsername();
        $author = $this->userRepository->findOneBy(
            ['username' => $user]
        );
        $task->setAuthor($author);
        $task->setCreatedAt(new DateTime());

        $this->taskRepository->create($task);
    }

    /**
     * Update a Task in db.
     *
     * @param Task $task
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateTask(Task $task): void
    {
        $this->taskRepository->update($task);
    }

    /**
     * Toggle a task status in db (done or to do).
     *
     * @param Task $task
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function toggle(Task $task): void
    {
        $task->toggle(!$task->isDone());
        $this->taskRepository->update($task);
    }

    /**
     * Delete a Task in db.
     *
     * @param Task $task
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteTask(Task $task): void
    {
        $this->taskRepository->delete($task);
    }
}
