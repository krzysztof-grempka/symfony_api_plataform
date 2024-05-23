<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\Service;

use App\Application\Message\Service\EmailSenderInterface;
use App\Domain\Message\Model\EmailMessage;
use App\Domain\User\Model\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender implements EmailSenderInterface
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function send(User $sender, User $recipient, EmailMessage $message): bool
    {
        return false;
    }

    public function sendBatch(string $subject, string $body, string $sender, string ...$recipients): void
    {
        $email = (new Email())
            ->from($sender)
            ->bcc(...$recipients)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}
