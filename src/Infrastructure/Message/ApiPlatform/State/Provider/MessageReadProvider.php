<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Application\Message\Query\FindMessageQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\Infrastructure\Shared\ApiPlatform\Factory\ResourceFactory;

class MessageReadProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof CollectionOperationInterface) {
            $model = $this->queryBus->ask(new FindMessageQuery($uriVariables['id']));
            if (!$model) {
                return null;
            }

            return ResourceFactory::fromModel($model->getResource(), $model);
        }

        return null;
    }
}
