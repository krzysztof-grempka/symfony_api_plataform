<?php

declare(strict_types=1);

namespace App\Application\User\Command\VerifyEmail;

use App\Application\Shared\Command\CommandInterface;

final class VerifyEmailCommand implements CommandInterface
{
    public function __construct(
        public string $verifyingToken,
    ) {
    }
}
