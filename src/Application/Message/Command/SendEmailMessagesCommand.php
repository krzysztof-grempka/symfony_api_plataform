<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Shared\Command\AsyncCommandInterface;
use Webmozart\Assert\Assert;

class SendEmailMessagesCommand implements AsyncCommandInterface
{
    public function __construct(public readonly int $message, public readonly array $recipients)
    {
        Assert::notEmpty($recipients);
        Assert::allInteger($recipients);
    }
}
