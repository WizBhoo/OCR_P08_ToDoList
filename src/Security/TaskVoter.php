<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class TaskVoter.
 */
class TaskVoter extends Voter
{
    /**
     * A constant that represent an action.
     *
     * @var string
     */
    const DELETE = 'delete';

    /**
     * An AuthorizationCheckerInterface Injection.
     *
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * TaskVoter constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritDoc}
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        /** @var Task $task */
        $task = $subject;

        if (null !== $task->getAuthor()) {
            return $user === $task->getAuthor();
        }

        return $this->authorizationChecker->isGranted('ROLE_ADMIN');
    }
}
