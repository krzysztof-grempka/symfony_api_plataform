<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Shared\Command\CommandInterface;
use Webmozart\Assert\Assert;

final class CreateEmployeeCommand implements CommandInterface
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $passwordRepeat,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null
    ) {
        Assert::email($email);
        Assert::minLength($password, 3);
        Assert::minLength($passwordRepeat, 3);
        Assert::nullOrLengthBetween($firstName, 2, 50);
        Assert::nullOrLengthBetween($lastName, 2, 50);
    }
}
