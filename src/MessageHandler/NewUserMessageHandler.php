<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\NewUserMessage;
use App\Util\MailSender;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewUserMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private MailSender $mailSender;
    private TranslatorInterface $translator;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, MailSender $mailSender, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->mailSender = $mailSender;
        $this->translator = $translator;
    }

    public function __invoke(NewUserMessage $message)
    {
        try {
            /** @var User $user */
            $user = $this->entityManager->getRepository(User::class)->find($message->getUserId());
            if (!$user) {
                throw new \Exception('User not found');
            }
            $this->mailSender->sendTwig($user->getEmail(), 'emails/register.html.twig', $this->translator->trans('email.register.subject'), []);

            $this->logger->notice('New user created (email: '.$user->getEmail().')');
        } catch (\Exception $e) {
            $this->logger->notice('New user handler error: '.$e->getMessage());
        }
    }
}
