<?php

declare(strict_types=1);

namespace App\Application\Message\Service;

use App\Domain\Message\Model\SmsMessage;
use App\Domain\User\Model\User;

interface SmsSenderInterface
{
    public function send(User $sender, User $recipient, SmsMessage $message): bool;

    public function sendBatch(string $body, string ...$recipients): bool;

    public function replaceAccents(string $str): string;
}
