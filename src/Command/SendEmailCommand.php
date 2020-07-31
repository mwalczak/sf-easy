<?php

declare(strict_types=1);

namespace App\Command;

use App\Util\MailSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

class SendEmailCommand extends Command
{
    protected static $defaultName = 'app:send-email';
    private MailSender $mailSender;

    /**
     * SendEmailCommand constructor.
     */
    public function __construct(MailSender $mailSender)
    {
        $this->mailSender = $mailSender;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Send a test email')
            ->addOption('template', null, InputOption::VALUE_REQUIRED, 'Template name from templates/emails')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email to')
            ->addOption('subject', null, InputOption::VALUE_OPTIONAL, 'Subject');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $this->mailSender->sendTwig(
                $input->getOption('email'),
                'emails/'.$input->getOption('template').'.html.twig',
                $input->getOption('subject') ?? 'Test subject',
                [
                    'resetToken' => new ResetPasswordToken('test', new \DateTime('tomorrow')),
                    'tokenLifetime' => new \DateTime('tomorrow'),
                ]
            );
            $io->success('Email sent to: '.$input->getOption('email'));
        } catch (\Exception $ex) {
            $io->error('Email error: '.$ex->getMessage());
        }

        return 0;
    }
}
