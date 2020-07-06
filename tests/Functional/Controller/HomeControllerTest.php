<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HomeControllerTest.
 */
class HomeControllerTest extends WebTestCase
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
     * Home page test.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $crawler = $this->client->request(
            'GET',
            '/'
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
