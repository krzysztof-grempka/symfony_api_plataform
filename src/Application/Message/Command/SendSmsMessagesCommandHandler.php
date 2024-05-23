<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Message\Service\SmsSenderInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Message\Model\MessageContext;
use App\Domain\Message\Repository\MessageContextRepositoryInterface;
use App\Domain\Message\Repository\MessageRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

class SendSmsMessagesCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SmsSenderInterface $smsSender,
        private readonly MessageRepositoryInterface $messageRepository,
        private readonly MessageContextRepositoryInterface $messageContextRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SendSmsMessagesCommand $command): void
    {
        try {
            $recipientsPhonesNumbers = $this->userRepository->getPhoneNumbersForIds(...$command->recipients);
            $message = $this->messageRepository->find($command->message);
            if (!$message || !$recipientsPhonesNumbers) {
                throw new \RuntimeException('Message could not be sent!');
            }
            $this->smsSender->sendBatch($message->body, ...$recipientsPhonesNumbers);
            $this->messageContextRepository->setStatusBatch($command->message, MessageContext::SENT);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageContextRepository->setStatusBatch($command->message, MessageContext::ERROR);
        }
    }
}
