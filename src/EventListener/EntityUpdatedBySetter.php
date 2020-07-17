<?php


namespace App\EventListener;


use App\Entity\UpdatedByInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityUpdatedBySetter
{
    private ?UserInterface $user;
    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function preUpdate(UpdatedByInterface $entity, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if($this->user){
            $entity->setUpdatedBy($this->user);
        }
    }

    public function prePersist(UpdatedByInterface $entity, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity->getUpdatedBy()) {
            return;
        }

        if($this->user){
            $entity->setUpdatedBy($this->user);
        }
    }
}