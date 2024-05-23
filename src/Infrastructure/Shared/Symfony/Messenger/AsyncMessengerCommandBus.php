<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Symfony\Messenger;

use App\Application\Shared\Command\AsyncCommandBusInterface;
use App\Application\Shared\Command\AsyncCommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

final class AsyncMessengerCommandBus implements AsyncCommandBusInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(AsyncCommandInterface $command, int $delay = 0): void
    {
        try {
            $this->handle($command, [new DelayStamp($delay)]);
        } catch (HandlerFailedException $e) {
            $exceptions = $e->getNestedExceptions();

            // throw current($exceptions);
            // @TODO: add log error
        }
    }

    private function handle(object $message, array $stamps): void
    {
        try {
            if (!isset($this->messageBus)) {
                throw new LogicException(sprintf('You must provide a "%s" instance in the "%s::$messageBus" property, but that property has not been initialized yet.', MessageBusInterface::class, static::class));
            }

            $this->messageBus->dispatch($message, $stamps);
        } catch (\Exception $e) {
            // @TODO: add log error
        }
    }
}
