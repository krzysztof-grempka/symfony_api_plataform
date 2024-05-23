<?php

namespace App\Infrastructure\User\Service;

use App\Application\User\Service\PasswordManagerInterface;
use App\Domain\User\Model\UserInterface;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordManager implements PasswordManagerInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function setPassword(UserInterface $user, string $password): void
    {
        $user->updatePassword(
            $this->passwordHasher->hashPassword($user, $password)
        );
    }

    public function updatePassword(UserInterface $user, string $newPassword, string $oldPassword): void
    {
        if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
            throw new InvalidPasswordException();
        }

        if (0 === strcmp($oldPassword, $newPassword)) {
            throw new InvalidPasswordException($this->translator->trans('user.password.diff', [], 'core'));
        }

        $user->updatePassword($this->passwordHasher->hashPassword($user, $newPassword));
    }
}
