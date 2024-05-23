<?php

namespace App\Application\User\Command\ResetPassword;

use App\Application\Shared\Command\CommandInterface;
use Webmozart\Assert\Assert;

final class SetNewPasswordCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $verifyingToken,
        public string $newPassword,
        public string $repeatPassword,
    ) {
        Assert::email($this->email);
    }
}
