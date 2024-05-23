<?php

namespace App\Application\User\Command\ResetPassword;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Repository\VerifyingTokenRepositoryInterface;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetNewPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly VerifyingTokenRepositoryInterface $tokenRepository
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(SetNewPasswordCommand $command): ?User
    {
        $validToken = $this->tokenRepository->verify($command->email);

        if ($command->verifyingToken !== $validToken->token) {
            throw new \Exception('Invalid token');
        }

        $user = $this->userRepository->findOneByEmail($command->email);
        if ($command->newPassword !== $command->repeatPassword) {
            throw new InvalidPasswordException('Different passwords');
        }
        $user->updatePassword($this->hasher->hashPassword($user, $command->newPassword));

        $this->userRepository->save($user);
        $this->tokenRepository->removeUsed($validToken);

        return $user;
    }
}
