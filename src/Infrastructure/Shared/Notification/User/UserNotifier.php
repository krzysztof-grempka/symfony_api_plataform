<?php

namespace App\Infrastructure\Shared\Notification\User;

use App\Application\Shared\Notification\UserNotifierInterface;
use App\Domain\User\Model\User;
use App\Infrastructure\Shared\Notification\User\Notifications\BlockUserNotification;
use App\Infrastructure\Shared\Notification\User\Notifications\ResetPasswordMessage;
use App\Infrastructure\Shared\Notification\User\Notifications\UnblockUserNotification;
use App\Infrastructure\Shared\Notification\User\Notifications\VerifyEmailMessage;

class UserNotifier implements UserNotifierInterface
{
    public function __construct(
        private readonly VerifyEmailMessage $verifyEmailMessage,
        private readonly ResetPasswordMessage $resetPasswordMessage,
        private readonly BlockUserNotification $blockUserNotification,
        private readonly UnblockUserNotification $unblockUserNotification,
    ) {
    }

    public function verifyEmail(User $user): void
    {
        $this->verifyEmailMessage->notify($user, ['email']);
    }

    public function resetPassword(User $user): void
    {
        $this->resetPasswordMessage->notify($user, ['email']);
    }

    public function blockUser(User $user, array $channels, string $reason): void
    {
        $this->blockUserNotification->notify($user, $channels, $reason);
    }

    public function unblockUser(User $user, array $channels): void
    {
        $this->unblockUserNotification->notify($user, $channels);
    }
}
