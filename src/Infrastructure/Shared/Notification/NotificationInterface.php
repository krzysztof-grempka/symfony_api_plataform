<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Notification;

use App\Domain\User\Model\User;

interface NotificationInterface
{
    public function notify(User $user, array $channels): void;
}
