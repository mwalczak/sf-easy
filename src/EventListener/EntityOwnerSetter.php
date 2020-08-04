<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\OwnedEntityInterface;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityOwnerSetter
{
    private ?UserInterface $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function prePersist(OwnedEntityInterface $entity, LifecycleEventArgs $args): void
    {
        if ($entity->getOwner()) {
            return;
        }
        /** @var User $user */
        $user = $this->user;

        if ($user) {
            $entity->setOwner($user);
        }
    }
}
