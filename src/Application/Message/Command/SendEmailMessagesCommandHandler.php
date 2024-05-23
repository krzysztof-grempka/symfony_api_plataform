<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Message\Service\EmailSenderInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Message\Model\MessageContext;
use App\Domain\Message\Repository\MessageContextRepositoryInterface;
use App\Domain\Message\Repository\MessageRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SendEmailMessagesCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EmailSenderInterface $emailSender,
        private readonly MessageRepositoryInterface $messageRepository,
        private readonly ParameterBagInterface $parameterBag,
        private readonly MessageContextRepositoryInterface $messageContextRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SendEmailMessagesCommand $command): void
    {
        try {
            $recipientsEmails = $this->userRepository->getEmailsForIds(...$command->recipients);
            $senderEmail = is_string($this->parameterBag->get('emailAddress')) ? $this->parameterBag->get('emailAddress') : '';
            $message = $this->messageRepository->find($command->message);
            if (!$message || !$recipientsEmails) {
                throw new \RuntimeException('Message could not be sent!');
            }
            $this->emailSender->sendBatch($message->subject, $message->body, $senderEmail, ...$recipientsEmails);
            $this->messageContextRepository->setStatusBatch($command->message, MessageContext::SENT);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageContextRepository->setStatusBatch($command->message, MessageContext::ERROR);
        }
    }
}
