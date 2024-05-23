<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

final class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(DeleteUserCommand $command): void
    {
        if (null === $user = $this->userRepository->find($command->id)) {
            return;
        }

        $this->userRepository->remove($user);
    }
}
