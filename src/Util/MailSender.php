<?php

declare(strict_types=1);

namespace App\Util;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailSender
{
    private string $mailerDsn;
    private string $mailerFrom;
    private Environment $twig;
    private LoggerInterface $logger;

    public function __construct(Environment $twig, LoggerInterface $logger)
    {
        $this->mailerDsn = $_ENV['MAILER_DSN'];
        $this->mailerFrom = $_ENV['MAILER_FROM'];
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function sendHtml(string $email, string $html, string $subject, ?string $text = null, ?string $fromName = null): string
    {
        $fromAddress = $fromName ? new Address($this->mailerFrom, $fromName) : new Address($this->mailerFrom);

        $message = (new Email())
            ->from($fromAddress)
            ->to($email)
            ->subject($subject)
            ->html($html);
        if(!empty($text)){
            $message->text($text);
        }
        return $this->send($message);
    }

    public function sendTwig(string $email, string $template, string $subject, array $context, string $from = null, string $reply = null){
        $from ??= new Address($_ENV['MAILER_FROM'], $_ENV['MAILER_FROM_NAME']);
        $message = (new TemplatedEmail())
            ->from($from)
            ->to($email)
            ->subject($subject);

        if(!empty($reply)){
            $message->replyTo($reply);
        }

        $context['email'] = new WrappedTemplatedEmail($this->twig, $message);

        $message->html($this->twig->render($template, $context));

        return $this->send($message);
    }

    private function send(Email $message){
        $transport = Transport::fromDsn($this->mailerDsn);
        $mailer = new Mailer($transport);

        try {
            $mailer->send($message);
            $this->logger->info('MailSender - email sent (subject: '.$message->getSubject().', to: '.$message->getTo()[0]->getAddress().', from: '.$message->getFrom()[0]->getAddress().')');
            return '';
        } catch (TransportExceptionInterface $ex) {
            return $ex->getMessage();
        }
    }
}
