<?php

namespace App\Application\User\Command\UserBlockade;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Model\User;

class CreateUserBlockadeCommand implements CommandInterface
{
    public function __construct(
        public readonly User $user,
        public readonly string $reason
    ) {
    }
}
