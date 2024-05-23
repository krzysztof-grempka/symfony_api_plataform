<?php

declare(strict_types=1);

namespace App\Application\User\Command\ChangePassword;

use App\Application\Shared\Command\CommandInterface;

final class ChangePasswordCommand implements CommandInterface
{
    public function __construct(
        public string $oldPassword,
        public string $newPassword1,
        public string $newPassword2,
    ) {
    }
}
