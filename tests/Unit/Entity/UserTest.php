<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest.
 */
class UserTest extends TestCase
{
    /**
     * A constant that represent a user username.
     *
     * @var string
     */
    const USER_USERNAME = 'Drixs6o9';

    /**
     * A constant that represent a user email.
     *
     * @var string
     */
    const USER_EMAIL = 'drixs6o9@prmaster.fr';

    /**
     * A constant that represent a user password.
     *
     * @var string
     */
    const USER_PASSWORD = 'password';

    /**
     * A constant that represent a user role.
     *
     * @var array
     */
    const USER_ROLES = ['ROLE_USER'];

    /**
     * Test User entity getters and setters.
     *
     * @return void
     */
    public function testGetterSetter(): void
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(null, $user->getId());
        $this->assertEquals(null, $user->getUsername());
        $this->assertEquals(null, $user->getEmail());
        $this->assertEquals(null, $user->getPassword());
        $this->assertEquals([], $user->getRoles());

        $user->setUsername(self::USER_USERNAME);
        $this->assertEquals(self::USER_USERNAME, $user->getUsername());
        $user->setEmail(self::USER_EMAIL);
        $this->assertEquals(self::USER_EMAIL, $user->getEmail());
        $user->setPassword(self::USER_PASSWORD);
        $this->assertEquals(self::USER_PASSWORD, $user->getPassword());
        $user->setRoles(self::USER_ROLES);
        $this->assertEquals(self::USER_ROLES, $user->getRoles());

        $task = new Task();
        $user->addTask($task);
        $this->assertCount(1, $user->getTasks());

        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());
    }
}
