<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Exception\MessageBusException;
use App\Application\Shared\Exception\UniqueConstraintException;
use App\Application\Shared\Message\MessageBusInterface;
use App\Application\User\Factory\UserFactoryInterface;
use App\Application\User\Message\EmployeeCreated;
use App\Domain\User\Model\Employee;
use App\Domain\User\Repository\UserRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

final class CreateEmployeeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserFactoryInterface $userFactory,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(CreateEmployeeCommand $command): Employee
    {
        try {
            $this->em->getConnection()->beginTransaction();

            $user = $this->userFactory->createEmployee($command);
            $this->userRepository->save($user);

            $this->em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $this->em->getConnection()->rollBack();
            throw new UniqueConstraintException($e->getMessage());
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }

        try {
            $this->messageBus->dispatch(new EmployeeCreated($user->id));
        } catch (\Throwable $e) {
            throw new MessageBusException($e->getMessage());
        }

        return $user;
    }
}
