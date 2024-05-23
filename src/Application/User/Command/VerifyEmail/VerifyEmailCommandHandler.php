<?php

declare(strict_types=1);

namespace App\Application\User\Command\VerifyEmail;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Repository\VerifyingTokenRepositoryInterface;

final class VerifyEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly VerifyingTokenRepositoryInterface $tokenRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(VerifyEmailCommand $command): void
    {
        $validToken = $this->tokenRepository->findOneBy(['token' => $command->verifyingToken]);

        $user = $this->userRepository->findOneByEmail($validToken->recipient);

        if ($command->verifyingToken !== $validToken->token) {
            throw new \Exception('Invalid token');
        }

        $user->verified = true;

        $this->userRepository->save($user);
        $this->tokenRepository->removeUsed($validToken);
    }
}
