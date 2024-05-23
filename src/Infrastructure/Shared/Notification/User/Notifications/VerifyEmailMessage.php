<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Notification\User\Notifications;

use App\Application\Shared\Notification\MessageRegisterInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Model\VerifyingToken;
use App\Domain\User\Repository\VerifyingTokenRepositoryInterface;
use App\Infrastructure\Shared\Notification\NotificationInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

final class VerifyEmailMessage implements NotificationInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MessageRegisterInterface $messageRegister,
        private readonly VerifyingTokenRepositoryInterface $tokenRepository,
        private readonly string $urlVerifyEmail,
    ) {
    }

    public function notify(User $user, array $channels): void
    {
        $token = Uuid::v4();

        $subject = $this->translator->trans('verify.subject', [], 'email');
        $body = $this->translator->trans('verify.body', ['%url%' => $this->urlVerifyEmail.$token], 'email');

        $verifyingToken = new VerifyingToken($user->getEmail(), (string) $token);
        $this->tokenRepository->generate($verifyingToken);

        $this->messageRegister->registerEmailMessage($subject, $body, $user);
    }
}
