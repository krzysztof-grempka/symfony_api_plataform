<?php

namespace App\Infrastructure\User\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Shared\Command\CommandBusInterface;
use App\Application\User\Command\UserBlockade\CreateUserBlockadeCommand;
use App\Application\User\Command\UserBlockade\DeleteUserBlockadeCommand;
use App\Application\User\Service\UserHelperInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\User\ApiPlatform\Resource\UserBlockadeResource;
use Webmozart\Assert\Assert;

class UserBlockadeCrudProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserHelperInterface $userHelper,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        Assert::isInstanceOf($data, UserBlockadeResource::class);

        if ($operation instanceof DeleteOperationInterface) {
            $this->commandBus->dispatch(new DeleteUserBlockadeCommand($uriVariables['id']));

            return null;
        }

        $user = match ($this->userHelper->getUserModel()->role) {
            User::ROLE_EMPLOYEE => $this->userHelper->getUserModel(),
            User::ROLE_ADMIN => $this->userRepository->find($data->user->id),
            default => null,
        };

        $command = new CreateUserBlockadeCommand(
            $user,
            $data->reason,
        );

        $model = $this->commandBus->dispatch($command);

        return UserBlockadeResource::fromModel($model);
    }
}
