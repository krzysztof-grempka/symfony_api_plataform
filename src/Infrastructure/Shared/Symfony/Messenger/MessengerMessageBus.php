<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Symfony\Messenger;

use App\Application\Shared\Message\MessageBusInterface;
use App\Application\Shared\Message\MessageInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface as BaseMessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

final class MessengerMessageBus implements MessageBusInterface
{
    public function __construct(private readonly BaseMessageBusInterface $messageBus)
    {
    }

    public function dispatch(MessageInterface $message, int $delay = 0): void
    {
        try {
            $this->handle($message, [new DelayStamp($delay)]);
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
                throw new LogicException(sprintf('You must provide a "%s" instance in the "%s::$messageBus" property, but that property has not been initialized yet.', BaseMessageBusInterface::class, static::class));
            }

            $this->messageBus->dispatch($message, $stamps);
        } catch (\Exception $e) {
            // @TODO: add log error
        }
    }
}
