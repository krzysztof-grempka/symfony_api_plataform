<?php

namespace App\Application\User\Command\UserBlockade;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserBlockadeRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class DeleteUserBlockadeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserBlockadeRepositoryInterface $blockadeRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(DeleteUserBlockadeCommand $command): void
    {
        if (null === $userBlockade = $this->blockadeRepository->find($command->id)) {
            return;
        }

        $user = $this->userRepository->find($userBlockade->user->id);
        $user->enabled = true;
        $this->userRepository->save($user);

        $this->blockadeRepository->remove($userBlockade);
    }
}
