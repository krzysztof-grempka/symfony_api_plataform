<?php

namespace App\Infrastructure\User\Factory;

use App\Application\Shared\Exception\InvalidPasswordException;
use App\Application\User\Command\CreateEmployeeCommand;
use App\Application\User\Factory\UserFactoryInterface;
use App\Application\User\Service\PasswordManagerInterface;
use App\Domain\User\Model\Employee;
use App\Domain\User\Model\User;

class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private readonly PasswordManagerInterface $passwordManager,
    ) {
    }

    public function createEmployee(CreateEmployeeCommand $command): Employee
    {
        $user = new Employee(
            $command->email,
            User::ROLE_EMPLOYEE,
            $command->firstName,
            $command->lastName
        );
        if ($command->password !== $command->passwordRepeat) {
            throw new InvalidPasswordException('Passwords are different.');
        }

        $this->passwordManager->setPassword($user, $command->password);

        return $user;
    }
}
