<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\ProjectComponentInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectComponentVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof ProjectComponentInterface;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var ProjectComponentInterface $component */
        $component = $subject;

        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
                if (in_array($component->getProject()->getId(), $user->getProjectsIdsWithAccess()) || $this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
        }

        return false;
    }
}