<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TaskRepository.
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    /**
     * TaskRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Persists new Task in db.
     *
     * @param Task $task
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Task $task): void
    {
        $this->_em->persist($task);
        $this->_em->flush();
    }

    /**
     * Persists Task updated in db.
     *
     * @param Task $task
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Task $task): void
    {
        $this->_em->persist($task);
        $this->_em->flush();
    }

    /**
     * Remove Task in db.
     *
     * @param Task $task
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Task $task): void
    {
        $this->_em->remove($task);
        $this->_em->flush();
    }
}
