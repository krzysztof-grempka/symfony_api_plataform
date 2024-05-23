<?php

namespace App\Application\User\Command\ChangePassword;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\User\Service\UserHelperInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ChangePasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserHelperInterface $userHelper
    ) {
    }

    public function __invoke(ChangePasswordCommand $command): User
    {
        if (!$user = $this->userHelper->getUserModel()) {
            throw new UnauthorizedHttpException('', 'User not found');
        }

        if (!$this->hasher->isPasswordValid($user, $command->oldPassword) or $command->newPassword1 !== $command->newPassword2) {
            throw new InvalidPasswordException('Incorrect old password or new passwords are different');
        }

        $user->updatePassword($this->hasher->hashPassword($user, $command->newPassword1));

        $this->userRepository->save($user);

        return $user;
    }
}
