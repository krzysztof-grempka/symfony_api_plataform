<?php

namespace App\Application\User\Service;

use App\Domain\User\Model\UserInterface;

interface PasswordManagerInterface
{
    public function setPassword(UserInterface $user, string $password): void;

    public function updatePassword(UserInterface $user, string $newPassword, string $oldPassword): void;
}
