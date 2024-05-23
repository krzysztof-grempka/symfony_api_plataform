<?php

namespace App\Application\User\Command\UserBlockade;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Model\UserBlockade;
use App\Domain\User\Repository\UserBlockadeRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateUserBlockadeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserBlockadeRepositoryInterface $blockadeRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(CreateUserBlockadeCommand $command): UserBlockade
    {
        $userBlockade = new UserBlockade(
            $command->user,
            $command->reason,
        );

        $command->user->enabled = false;

        $this->userRepository->save($command->user);
        $this->blockadeRepository->save($userBlockade);

        return $userBlockade;
    }
}
