<?php

namespace App\Infrastructure\User\ApiPlatform\State\Processor\ResetPassword;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Shared\Command\CommandBusInterface;
use App\Application\User\Command\ResetPassword\SetNewPasswordCommand;
use App\Infrastructure\Shared\ApiPlatform\Factory\ResourceFactory;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;
use Webmozart\Assert\Assert;

class SetNewPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        Assert::isInstanceOf($data, SetNewPasswordCommand::class);

        $command = new SetNewPasswordCommand(
            $data->email,
            $data->verifyingToken,
            $data->newPassword,
            $data->repeatPassword,
        );

        $model = $this->commandBus->dispatch($command);

        return ResourceFactory::fromModel(UserResource::class, $model);
    }
}
