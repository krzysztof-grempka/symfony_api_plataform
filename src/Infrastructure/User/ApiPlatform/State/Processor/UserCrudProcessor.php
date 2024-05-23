<?php

declare(strict_types=1);

namespace App\Infrastructure\User\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Shared\Command\CommandBusInterface;
use App\Application\User\Command\CreateEmployeeCommand;
use App\Application\User\Command\DeleteUserCommand;
use App\Application\User\Command\UpdateUserCommand;
use App\Domain\User\Model\User;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;
use Webmozart\Assert\Assert;

final class UserCrudProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        Assert::isInstanceOf($data, UserResource::class);

        if ($operation instanceof DeleteOperationInterface) {
            $this->commandBus->dispatch(new DeleteUserCommand($uriVariables['id']));

            return null;
        }

        $command = !isset($uriVariables['id'])
            ? new CreateEmployeeCommand(
                $data->email,
                $data->password,
                $data->passwordRepeat,
                $data->firstName,
                $data->lastName
            )
            : new UpdateUserCommand(
                $uriVariables['id'],
                $data->firstName,
                $data->lastName,
            )
        ;

        /** @var User $model */
        $model = $this->commandBus->dispatch($command);

        return UserResource::fromModel($model);
    }
}
