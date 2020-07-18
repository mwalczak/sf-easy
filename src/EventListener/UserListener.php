<?php


namespace App\EventListener;


use App\Entity\User;
use App\Message\NewUserMessage;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private MessageBusInterface $bus;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, MessageBusInterface $bus)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->bus = $bus;
    }

    public function preUpdate(User $user, LifecycleEventArgs $args): void
    {
        $this->handlePassword($user);
    }

    public function prePersist(User $user, LifecycleEventArgs $args): void
    {
        $this->handlePassword($user);
    }

    public function postPersist(User $user, LifecycleEventArgs $args): void
    {
        $this->bus->dispatch(new NewUserMessage($user->getId()));
    }

    private function handlePassword(User $user): void
    {
        if($user->getPlainPassword()){
            $user->setPassword(
                $this->userPasswordEncoder->encodePassword($user, $user->getPlainPassword())
            );

            $user->eraseCredentials();
        }
    }
}