<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Notification\User\Notifications;

use App\Application\Shared\Notification\MessageRegisterInterface;
use App\Domain\User\Model\User;
use App\Infrastructure\Shared\Notification\NotificationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UnblockUserNotification implements NotificationInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MessageRegisterInterface $messageRegister,
    ) {
    }

    public function notify(User $user, array $channels): void
    {
        $subject = $this->translator->trans('user_block.subject.unblocked', [], 'email');
        $body = $this->translator->trans('user_block.body.unblocked', [], 'email');

        $this->messageRegister->registerMessage($subject, $body, $user, $channels);
    }
}
