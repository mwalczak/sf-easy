<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Project;
use App\Entity\ProjectComponentInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private CrudUrlGenerator $crudUrlGenerator;
    private SessionInterface $session;

    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => ['resetUserPassword'],
            BeforeCrudActionEvent::class => ['filterUsers'],
        ];
    }

    public function resetUserPassword(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $entity->cleanPassword();
    }

    public function filterUsers(BeforeCrudActionEvent $event): void
    {
        $context = $event->getAdminContext();

        if (!(new \ReflectionClass($context->getEntity()->getFqcn()))->implementsInterface(ProjectComponentInterface::class)) {
            return;
        }

        /** @var ProjectComponentInterface $projectComponent */
        $projectComponent = $context->getEntity()->getInstance();
        if (!$projectComponent) {
            return;
        }
        /** @var Project $project */
        $project = $projectComponent->getProject();
        if (!$project) {
            return;
        }

        $users = array_keys($project->getUsersWithAccess());
        $this->entityManager->getFilters()->enable('user_filter')->setParameter('user', implode(',', array_unique($users)));
    }
}
