<?php

declare(strict_types=1);

namespace App\Application\Message\Service;

use App\Domain\Message\Model\EmailMessage;
use App\Domain\User\Model\User;

interface EmailSenderInterface
{
    public function send(User $sender, User $recipient, EmailMessage $message): bool;

    public function sendBatch(string $subject, string $body, string $sender, string ...$recipients): void;
}
