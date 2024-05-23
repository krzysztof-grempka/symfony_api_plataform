<?php

namespace App\Application\User\Message;

use App\Application\Shared\Message\MessageInterface;

class EmployeeCreated implements MessageInterface
{
    public function __construct(public readonly int $userId)
    {
    }
}
