<?php

declare(strict_types=1);

namespace App\Application\Shared\Message;

interface MessageBusInterface
{
    public function dispatch(MessageInterface $message): void;
}
