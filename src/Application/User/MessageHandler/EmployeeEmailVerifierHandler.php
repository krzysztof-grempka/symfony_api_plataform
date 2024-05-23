<?php

namespace App\Application\User\MessageHandler;

use App\Application\Shared\Message\MessageHandlerInterface;
use App\Application\Shared\Notification\UserNotifierInterface;
use App\Application\User\Message\EmployeeCreated;
use App\Domain\User\Repository\UserRepositoryInterface;

class EmployeeEmailVerifierHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserNotifierInterface $notifier,
    ) {
    }

    public function __invoke(EmployeeCreated $message): void
    {
        try {
            $this->notifier->verifyEmail($this->userRepository->getReference($message->userId));
        } catch (\Exception $e) {
            // @TODO: add log error
        }
    }
}
