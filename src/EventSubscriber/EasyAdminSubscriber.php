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

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private CrudUrlGenerator $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => ['resetUserPassword'],
            BeforeCrudActionEvent::class => ['checkUserUnique'],
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

    public function checkUserUnique(BeforeCrudActionEvent $event): void
    {
        $context = $event->getAdminContext();

        if (!($user = $context->getRequest()->get('User'))) {
            return;
        }

        if ($this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $user['email'],
        ])) {
            $url = $this->crudUrlGenerator
                ->build()
                ->setController(UserCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();
            //TODO: add flash
            $response = new RedirectResponse($url);

            $event->setResponse($response);
        }
    }
}
