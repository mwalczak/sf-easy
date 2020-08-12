<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
}
