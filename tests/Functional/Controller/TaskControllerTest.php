<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Tests\Functional\Controller;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TaskControllerTest.
 */
class TaskControllerTest extends WebTestCase
{
    /**
     * Helper to access test Client.
     *
     * @var KernelBrowser
     */
    private $client;

    /**
     * An EntityManager Instance.
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Set up the client and the EntityManager.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient(
            ['environment' => 'test'],
            [
                'PHP_AUTH_USER' => 'user1',
                'PHP_AUTH_PW'   => 'demo1',
            ]
        );
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Test show Tasks list to do.
     *
     * @return void
     */
    public function testShowTasksListToDo(): void
    {
        $this->client->request(
            'GET',
            '/tasks/todo'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Test show Tasks list done.
     *
     * @return void
     */
    public function testShowTasksListDone(): void
    {
        $this->client->request(
            'GET',
            '/tasks/done'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Test create Task by current authenticated User.
     *
     * @return void
     */
    public function testCreateTask(): void
    {
        $crawler = $this->client->request(
            'GET',
            '/tasks/create'
        );

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre de la tâche';
        $form['task[content]'] = 'Ceci est la description de la tâche.';
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "La tâche a été bien été ajoutée.",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['title' => 'Titre de la tâche']);
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('user1', $task->getAuthor()->getUsername());
        $this->assertInstanceOf(User::class, $task->getAuthor());
    }

    /**
     * Test edit Task.
     *
     * @return void
     */
    public function testEditTask(): void
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(5);
        $crawler = $this->client->request(
            'GET',
            '/tasks/'.$task->getId().'/edit'
        );

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'newTitle';
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "La tâche a bien été modifiée.",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(5);
        $this->assertSame('newTitle', $task->getTitle());
    }

    /**
     * Test delete Task by its author.
     * (user1 try to delete task created by him)
     *
     * @return void
     */
    public function testDeleteTask(): void
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(6);
        $this->client->request(
            'DELETE',
            '/tasks/'.$task->getId().'/delete'
        );

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "La tâche a bien été supprimée.",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(6);
        $this->assertEquals(null, $task);
    }

    /**
     * Test delete Task created by another Author.
     * (user1 try to delete task created by user2)
     *
     * @return void
     */
    public function testDeleteTaskOfOtherAuthor(): void
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(12);
        $this->client->request(
            'DELETE',
            '/tasks/'.$task->getId().'/delete'
        );

        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test delete Task created by anonymous Author.
     * (user1 try to delete task created by anonymous author)
     *
     * @return void
     */
    public function testDeleteTaskOfAnonymousAuthor(): void
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(1);
        $this->client->request(
            'DELETE',
            '/tasks/'.$task->getId().'/delete'
        );

        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test delete Anonymous Task by Admin user.
     * (user2 try to delete task created by anonymous author)
     *
     * @return void
     */
    public function testDeleteAnonymousTaskByAdmin(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient(
            ['environment' => 'test'],
            [
                'PHP_AUTH_USER' => 'user2',
                'PHP_AUTH_PW'   => 'demo2',
            ]
        );

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(1);
        $client->request(
            'DELETE',
            '/tasks/'.$task->getId().'/delete'
        );

        $session = $client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "La tâche a bien été supprimée.",
            current($flashes['success'])
        );
    }

    /**
     * Test to toggle a task as Done.
     *
     * @return void
     */
    public function testToggleTaskDone(): void
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(5);
        $this->client->request(
            'GET',
            '/tasks/'.$task->getId().'/toggle'
        );

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            'La tâche '.$task->getTitle().' a bien été marquée comme faite.',
            current($flashes['success'])
        );
        $this->client->followRedirect();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(5);
        $this->assertTrue($task->isDone());
    }

    /**
     * Test to toggle a Task as To Do.
     *
     * @return void
     */
    public function testToggleTaskToDo(): void
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(5);
        $task->toggle(!$task->isDone());

        $this->client->request(
            'GET',
            '/tasks/'.$task->getId().'/toggle'
        );

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            'La tâche '.$task->getTitle().' a bien été rebasculée dans les tâches à faire.',
            current($flashes['success'])
        );
        $this->client->followRedirect();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find(5);
        $this->assertFalse($task->isDone());
    }

    /**
     * Called after each test using entityManager to avoid memory leaks.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
