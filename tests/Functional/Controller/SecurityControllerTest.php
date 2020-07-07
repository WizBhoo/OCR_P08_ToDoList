<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * Helper to access test Client.
     *
     * @var KernelBrowser
     */
    private $client;

    /**
     * Set up the client.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient(
            ['environment' => 'test']
        );
    }

    /**
     * Login test.
     *
     * @return void
     */
    public function testLogin(): void
    {
        $crawler = $this->client->request(
            'GET',
            '/login'
        );

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'user2';
        $form['password'] = 'demo2';
        $this->client->submit($form);

        $this->assertResponseRedirects('/');
        $crawler = $this->client->followRedirect();
        $this->assertSame(0, $crawler->filter('div.alert.alert-danger')->count());
    }

    /**
     * Test to access protected URI with login.
     *
     * @return void
     */
    public function testLoginProtectedUri(): void
    {
        $this->client->request(
            'GET',
            '/tasks/todo'
        );

        $crawler = $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'user2';
        $form['password'] = 'demo2';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());
        $crawler = $this->client->followRedirect();
        $this->assertSame(0, $crawler->filter('div.alert.alert-danger')->count());
    }

    /**
     * Test to access User Admin by User without ROLE_ADMIN.
     *
     * @return void
     */
    public function testLoginUserAdmin(): void
    {
        $this->client->request(
            'GET',
            '/users'
        );

        $crawler = $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'user1';
        $form['password'] = 'demo1';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());
        $this->client->followRedirect();
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Login test with bad user.
     *
     * @return void
     */
    public function testLoginBadUser(): void
    {
        $crawler = $this->client->request(
            'GET',
            '/login'
        );

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'baduser1';
        $form['password'] = 'wrongpass1';
        $this->client->submit($form);

        $this->assertResponseRedirects('/login');
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }

    /**
     * Login test with bad csrf token.
     *
     * @return void
     */
    public function testLoginBadToken(): void
    {
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');

        $this->client->request(
            'POST',
            '/login',
            [
                'username' => 'user1',
                'password' => 'demo1',
                '_token' => $csrfToken,
            ]
        );

        $this->assertResponseRedirects('/login');
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }

    /**
     * Logout test.
     *
     * @return void
     */
    public function testLogout(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient(
            ['environment' => 'test'],
            [
                'PHP_AUTH_USER' => 'user1',
                'PHP_AUTH_PW'   => 'demo1',
            ]
        );

        $client->request('GET', '/logout');

        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }
}
