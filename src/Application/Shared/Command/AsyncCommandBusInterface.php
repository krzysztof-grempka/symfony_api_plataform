<?php

declare(strict_types=1);

namespace App\Application\Shared\Command;

interface AsyncCommandBusInterface
{
    public function dispatch(AsyncCommandInterface $command, int $delay = 0): void;
}
