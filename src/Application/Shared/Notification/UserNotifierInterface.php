<?php

declare(strict_types=1);

namespace App\Application\Shared\Notification;

use App\Domain\User\Model\User;

interface UserNotifierInterface
{
    public function verifyEmail(User $user): void;

    public function resetPassword(User $user): void;

    public function blockUser(User $user, array $channels, string $reason): void;

    public function unblockUser(User $user, array $channels): void;
}
