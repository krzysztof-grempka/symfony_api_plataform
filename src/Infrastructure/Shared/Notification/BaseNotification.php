<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Notification;

use Symfony\Component\Notifier\NotifierInterface;

class BaseNotification
{
    protected NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }
}
