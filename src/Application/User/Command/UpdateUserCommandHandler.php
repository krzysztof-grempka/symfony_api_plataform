<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(UpdateUserCommand $command): User
    {
        $user = $this->userRepository->find($command->id);
        if (null === $user) {
            throw new NotFoundHttpException('Not found');
        }

        $user->firstName = $command->firstName ?? $user->firstName;
        $user->lastName = $command->lastName ?? $user->lastName;
        $user->email = $command->email ?? $user->email;

        $this->userRepository->save($user);

        return $user;
    }
}
