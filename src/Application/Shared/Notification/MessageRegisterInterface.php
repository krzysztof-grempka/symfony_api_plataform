<?php

declare(strict_types=1);

namespace App\Application\Shared\Notification;

use App\Domain\Message\Model\Message;
use App\Domain\Message\Model\MessageContext;
use App\Domain\User\Model\User;

interface MessageRegisterInterface
{
    public function sendEmailMessage(string $subject, string $body, MessageContext $messageContext, Message $message): void;

    public function sendEmailMessageForNotRegistered(string $subject, string $body, string $email, MessageContext $messageContext, Message $message): void;

    public function registerMessage(string $subject, string $body, User $user, array $channels): void;

    public function registerEmailMessage(string $subject, string $body, User $user): void;

    public function registerEmailMessageForNotRegisteredUser(string $subject, string $body, User $user, string $email): void;
}
