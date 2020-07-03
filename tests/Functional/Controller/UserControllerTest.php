<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
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
     * Set up the EntityManager.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient(
            ['environment' => 'test']
        );
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Test show Users list.
     *
     * @return void
     */
    public function testShowUsersList(): void
    {
        $this->client->request(
            'GET',
            '/users'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Test create User.
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        $crawler = $this->client->request(
            'GET',
            '/users/create'
        );

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'user3';
        $form['user[password][first]'] = 'demo3';
        $form['user[password][second]'] = 'demo3';
        $form['user[email]'] = 'user3@todo-co.com';
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "L'utilisateur a bien été ajouté.",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user3']);
        $this->assertInstanceOf(User::class, $user);
    }


    /**
     * Test edit User.
     *
     * @return void
     */
    public function testEditUser(): void
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user2']);
        $crawler = $this->client->request(
            'GET',
            '/users/'.$user->getId().'/edit'
        );

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'newUser2';
        $form['user[password][first]'] = 'demo3';
        $form['user[password][second]'] = 'demo3';
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "L'utilisateur a bien été modifié",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'user2@todo-co.com']);
        $this->assertSame('newUser2', $user->getUsername());
    }

    /**
     * Test edit User.
     *
     * @return void
     */
    public function testDeleteUser(): void
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user2']);
        $crawler = $this->client->request(
            'DELETE',
            '/users/'.$user->getId().'/delete'
        );

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "L'utilisateur a bien été supprimé",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user2']);
        $this->assertEquals(null, $user);
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
