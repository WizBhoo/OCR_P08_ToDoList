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
     * Set up the EntityManager.
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
