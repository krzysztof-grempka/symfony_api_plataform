<?php

declare(strict_types=1);

namespace App\Infrastructure\User\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Shared\Command\CommandBusInterface;
use App\Application\User\Command\ChangePassword\ChangePasswordCommand;
use App\Infrastructure\Shared\ApiPlatform\Factory\ResourceFactory;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;
use Webmozart\Assert\Assert;

final class UpdatePasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        Assert::isInstanceOf($data, ChangePasswordCommand::class);

        $command = new ChangePasswordCommand(
            $data->oldPassword,
            $data->newPassword1,
            $data->newPassword2,
        );

        $model = $this->commandBus->dispatch($command);

        return ResourceFactory::fromModel(UserResource::class, $model);
    }
}
