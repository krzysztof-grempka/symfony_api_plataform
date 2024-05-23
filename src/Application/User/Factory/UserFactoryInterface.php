<?php

namespace App\Application\User\Factory;

use App\Application\User\Command\CreateEmployeeCommand;
use App\Domain\User\Model\Employee;

interface UserFactoryInterface
{
    public function createEmployee(CreateEmployeeCommand $command): Employee;
}
