<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Notification;

use App\Application\Message\Service\SmsSenderInterface;
use App\Application\Shared\Notification\MessageRegisterInterface;
use App\Domain\Message\Model\EmailMessage;
use App\Domain\Message\Model\Message;
use App\Domain\Message\Model\MessageContext;
use App\Domain\Message\Repository\MessageContextRepositoryInterface;
use App\Domain\Message\Repository\MessageRepositoryInterface;
use App\Domain\User\Model\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MessageRegister extends BaseNotification implements MessageRegisterInterface
{
    public function __construct(
        NotifierInterface $notifier,
        private readonly MessageRepositoryInterface $messageRepository,
        private readonly MessageContextRepositoryInterface $messageContextRepository,
        private readonly LoggerInterface $logger,
        private readonly MailerInterface $mailer,
        //        private readonly SmsSenderInterface $smsSender,
        private readonly string $emailAddress,
    ) {
        parent::__construct($notifier);
    }

    public function registerMessage(string $subject, string $body, User $user, array $channels): void
    {
        if (in_array('email', $channels)) {
            $this->registerEmailMessage($subject, $body, $user);
        }
    }

    public function sendEmailMessageForNotRegistered(string $subject, string $body, string $email, MessageContext $messageContext, Message $message): void
    {
        $email = (new Email())
            ->from($this->emailAddress)
            ->to($email)
            ->subject($subject)
            ->html($body);

        try {
            $this->mailer->send($email);
            $this->messageContextRepository->setStatusBatch($message->id, MessageContext::SENT);
            $messageContext->sentAt = new \DateTimeImmutable();
            $this->messageContextRepository->save($messageContext);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageContextRepository->setStatusBatch($message->id, MessageContext::ERROR);
        } catch (TransportExceptionInterface $e) {
        }
    }

    public function sendSmsMessage(Notification $notification, Recipient $recipient, MessageContext $messageContext, Message $message): void
    {
        try {
            $this->notifier->send($notification, $recipient);
            $this->messageContextRepository->setStatusBatch($message->id, MessageContext::SENT);
            $messageContext->sentAt = new \DateTimeImmutable();
            $this->messageContextRepository->save($messageContext);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageContextRepository->setStatusBatch($message->id, MessageContext::ERROR);
        }
    }

    public function sendEmailMessage(string $subject, string $body, MessageContext $messageContext, Message $message): void
    {
        $email = (new Email())
            ->from($this->emailAddress)
            ->to($messageContext->recipient->email)
            ->subject($subject)
            ->html($body);

        try {
            $this->mailer->send($email);
            $this->messageContextRepository->setStatusBatch($message->id, MessageContext::SENT);
            $messageContext->sentAt = new \DateTimeImmutable();
            $this->messageContextRepository->save($messageContext);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageContextRepository->setStatusBatch($message->id, MessageContext::ERROR);
        } catch (TransportExceptionInterface $e) {
        }
    }

    public function registerEmailMessage(string $subject, string $body, User $user): void
    {
        $message = new EmailMessage($subject, $body);
        $messageContext = new MessageContext($user, $user, $message);
        $messageContext->status = MessageContext::CREATED;

        $this->messageRepository->save($message);
        $this->messageContextRepository->save($messageContext);

        $this->sendEmailMessage($subject, $body, $messageContext, $message);
    }

    public function registerEmailMessageForNotRegisteredUser(string $subject, string $body, User $user, string $email): void
    {
        $message = new EmailMessage($subject, $body);
        $messageContext = new MessageContext($user, $user, $message);
        $messageContext->status = MessageContext::CREATED;

        $this->messageRepository->save($message);
        $this->messageContextRepository->save($messageContext);

        $this->sendEmailMessageForNotRegistered($subject, $body, $email, $messageContext, $message);
    }
}
