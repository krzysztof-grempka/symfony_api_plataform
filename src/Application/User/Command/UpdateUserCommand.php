<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Shared\Command\CommandInterface;
use Webmozart\Assert\Assert;

final class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $email = null,
    ) {
        Assert::nullOrLengthBetween($firstName, 2, 50);
        Assert::nullOrLengthBetween($lastName, 2, 50);
        Assert::String($this->firstName, 'Imię powinno zawierać tylko litery');
        Assert::String($this->lastName, 'Nazwisko powinno zawierać tylko litery');
    }
}
