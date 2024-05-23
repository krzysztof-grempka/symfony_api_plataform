<?php

namespace App\Application\User\Command\ResetPassword;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Notification\UserNotifierInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserNotifierInterface $userNotifier,
    ) {
    }

    public function __invoke(SendEmailCommand $command): User
    {
        $user = $this->userRepository->findOneByEmail($command->email);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $this->userNotifier->resetPassword($user);

        return $user;
    }
}
