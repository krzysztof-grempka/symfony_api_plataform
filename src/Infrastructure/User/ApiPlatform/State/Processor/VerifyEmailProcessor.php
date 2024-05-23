<?php

declare(strict_types=1);

namespace App\Infrastructure\User\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Shared\Command\CommandBusInterface;
use App\Application\User\Command\VerifyEmail\VerifyEmailCommand;
use Webmozart\Assert\Assert;

final class VerifyEmailProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        Assert::isInstanceOf($data, VerifyEmailCommand::class);

        $command = new VerifyEmailCommand(
            $data->verifyingToken,
        );

        $this->commandBus->dispatch($command);
    }
}
