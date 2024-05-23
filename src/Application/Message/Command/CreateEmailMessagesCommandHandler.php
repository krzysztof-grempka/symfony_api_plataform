<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Message\Factory\MessageFactory;
use App\Application\Shared\Command\AsyncCommandBusInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\User\Service\UserHelperInterface;
use App\Domain\Message\Model\MessageContext;
use App\Domain\Message\Repository\MessageContextRepositoryInterface;
use App\Domain\Message\Repository\MessageRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class CreateEmailMessagesCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly MessageFactory $messageFactory,
        private readonly MessageRepositoryInterface $messageRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly MessageContextRepositoryInterface $messageContextRepository,
        private readonly UserHelperInterface $userHelper,
        private readonly AsyncCommandBusInterface $asyncCommandBus
    ) {
    }

    public function __invoke(CreateEmailMessagesCommand $command): void
    {
        if (count(array_unique($command->recipients)) !== count($command->recipients)) {
            throw new BadRequestHttpException('Duplicated recipients detected');
        }

        if (!$this->userRepository->everyExist(...$command->recipients)) {
            throw new UnprocessableEntityHttpException('Cannot find every recipient on the list');
        }

        $message = $this->messageFactory->createEmailMessage($command->subject, $command->body);
        $this->messageRepository->save($message);
        $sendEmailMessagesCommand = new SendEmailMessagesCommand($message->id, $command->recipients);
        $sender = $this->userHelper->getUserModel();

        try {
            if (!$sender) {
                throw new \RuntimeException('Cannot send message with no user logged in!');
            }
            $this->messageContextRepository->saveBatch($sender->getId(), $sendEmailMessagesCommand->message, ...$command->recipients);
            $this->asyncCommandBus->dispatch($sendEmailMessagesCommand, 100);
            $this->messageContextRepository->setStatusBatch($sendEmailMessagesCommand->message, MessageContext::QUEUE);
        } catch (\Exception) {
            $this->messageContextRepository->setStatusBatch($sendEmailMessagesCommand->message, MessageContext::ERROR);
        }
    }
}
