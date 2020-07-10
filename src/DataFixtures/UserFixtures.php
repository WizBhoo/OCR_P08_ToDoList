<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends Fixture
{
    /**
     * A UserPasswordEncoderInterface Injection.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * A prefix reference constant for User.
     *
     * @var string
     */
    public const AUTHOR_REFERENCE_PREFIX = 'author_';

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load fixtures in User table.
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 3; $i++) {
            $user = new User();
            $user
                ->setUsername('user'.$i)
                ->setEmail('user'.$i.'@todo-co.com')
                ->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        'demo'.$i
                    )
                )
            ;
            if ($i === 1) {
                $user->setRoles(['ROLE_USER']);
            } elseif ($i === 2) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $manager->persist($user);
            $this->addReference(self::AUTHOR_REFERENCE_PREFIX.$i, $user);
        }

        $manager->flush();
    }
}
