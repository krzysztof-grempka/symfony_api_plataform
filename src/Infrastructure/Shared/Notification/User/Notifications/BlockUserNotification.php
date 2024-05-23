<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Notification\User\Notifications;

use App\Application\Shared\Notification\MessageRegisterInterface;
use App\Domain\User\Model\User;
use App\Infrastructure\Shared\Notification\NotificationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlockUserNotification implements NotificationInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MessageRegisterInterface $messageRegister,
    ) {
    }

    public function notify(User $user, array $channels, string $reason = ''): void
    {
        $subject = $this->translator->trans('user_block.subject.blocked', [], 'email');
        $body = $this->translator->trans('user_block.body.blocked', ['%reason%' => $reason], 'email');

        $this->messageRegister->registerMessage($subject, $body, $user, $channels);
    }
}
