<?php

namespace App\Application\User\Command\UserBlockade;

use App\Application\Shared\Command\CommandInterface;

class DeleteUserBlockadeCommand implements CommandInterface
{
    public function __construct(
        public readonly int $id,
    ) {
    }
}
