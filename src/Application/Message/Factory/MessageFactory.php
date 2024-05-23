<?php

declare(strict_types=1);

namespace App\Application\Message\Factory;

use App\Domain\Message\Model\EmailMessage;
use App\Domain\Message\Model\SmsMessage;

class MessageFactory
{
    public function __construct()
    {
    }

    public function createEmailMessage(string $subject, string $body): EmailMessage
    {
        return new EmailMessage($subject, $body);
    }

    public function createSmsMessage(string $body): SmsMessage
    {
        return new SmsMessage($body);
    }
}
