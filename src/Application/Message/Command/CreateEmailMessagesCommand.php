<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Shared\Command\CommandInterface;
use Webmozart\Assert\Assert;

final class CreateEmailMessagesCommand implements CommandInterface
{
    public function __construct(
        public readonly string $subject,
        public readonly string $body,
        public readonly array $recipients,
    ) {
        Assert::minLength($subject, 8);
        Assert::minLength($body, 8);
        Assert::notEmpty($recipients);
        Assert::allInteger($recipients);
    }
}
