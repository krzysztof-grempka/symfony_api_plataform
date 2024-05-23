<?php

declare(strict_types=1);

namespace App\Application\User\Command\ResetPassword;

use App\Application\Shared\Command\CommandInterface;
use Webmozart\Assert\Assert;

final class SendEmailCommand implements CommandInterface
{
    public function __construct(
        public string $email,
    ) {
        Assert::email($this->email);
    }
}
