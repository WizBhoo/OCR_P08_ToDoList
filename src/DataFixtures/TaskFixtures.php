<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\DataFixtures;

use App\Entity\Task;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TaskFixtures.
 */
class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load fixtures in Task table.
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 16; $i++) {
            $task = new Task();
            $task
                ->setTitle('task'.$i)
                ->setContent(
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce varius at ligula nec sollicitudin.'
                )
                ->setCreatedAt(new DateTime())
            ;
            if ($i < 6) {
                $task->setAuthor(null);
            } elseif ($i > 5 && $i < 11) {
                $task->setAuthor(
                    $this->getReference(UserFixtures::AUTHOR_REFERENCE_PREFIX.'1')
                );
            } elseif ($i > 10) {
                $task->setAuthor(
                    $this->getReference(UserFixtures::AUTHOR_REFERENCE_PREFIX.'2')
                );
            }

            $manager->persist($task);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
