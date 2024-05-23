<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Message\Command\CreateEmailMessagesCommand;
use App\Application\Shared\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Webmozart\Assert\Assert;

final class EmailMessageContextCreateProcessor implements ProcessorInterface
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [],
        array $context = []): JsonResponse
    {
        Assert::isInstanceOf($data, CreateEmailMessagesCommand::class);
        $command = new CreateEmailMessagesCommand(
            $data->subject,
            $data->body,
            $data->recipients
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(status: 202);
    }
}
