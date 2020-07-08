<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Class TaskTest.
 */
class TaskTest extends TestCase
{
    /**
     * A constant that represent a task title.
     *
     * @var string
     */
    const TASK_TITLE = 'Ceci est une tâche';

    /**
     * A constant that represent a task content.
     *
     * @var string
     */
    const TASK_CONTENT = "Ceci est la description d'une tâche";

    /**
     * Test Task entity getters and setters.
     *
     * @return void
     */
    public function testGetterSetter(): void
    {
        $task = new Task();

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(null, $task->getId());
        $this->assertEquals(null, $task->getTitle());
        $this->assertEquals(null, $task->getContent());
        $this->assertEquals(null, $task->getAuthor());
        $this->assertEquals(false, $task->isDone());
        $this->assertEquals(null, $task->getCreatedAt());

        $task->setTitle(self::TASK_TITLE);
        $this->assertEquals(self::TASK_TITLE, $task->getTitle());
        $task->setContent(self::TASK_CONTENT);
        $this->assertEquals(self::TASK_CONTENT, $task->getContent());
        $task->setCreatedAt(new DateTime());
        $this->assertInstanceOf(DateTime::class, $task->getCreatedAt());
        $task->toggle(!$task->isDone());
        $this->assertTrue($task->isDone());

        $user = new User();
        $task->setAuthor($user);
        $this->assertInstanceOf(User::class, $task->getAuthor());
    }
}
