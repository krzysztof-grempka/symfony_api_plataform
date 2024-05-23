<?php

namespace App\Infrastructure\User\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Application\Shared\Query\QueryBusInterface;
use App\Application\User\Query\UserBlockade\FindUserBlockadeCollectionQuery;
use App\Application\User\Query\UserBlockade\FindUserBlockadeQuery;
use App\Infrastructure\Shared\ApiPlatform\State\Paginator;
use App\Infrastructure\User\ApiPlatform\Resource\UserBlockadeResource;

class UserBlockadeCrudProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof CollectionOperationInterface) {
            $model = $this->queryBus->ask(new FindUserBlockadeQuery($uriVariables['id']));

            return null !== $model ? UserBlockadeResource::fromModel($model) : null;
        }

        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        $models = $this->queryBus->ask(new FindUserBlockadeCollectionQuery($offset, $limit));

        $resources = [];
        foreach ($models as $model) {
            $resources[] = UserBlockadeResource::fromModel($model);
        }

        if (null !== $paginator = $models->paginator()) {
            $resources = new Paginator(
                $resources,
                (float) $paginator->getCurrentPage(),
                (float) $paginator->getItemsPerPage(),
                (float) $paginator->getLastPage(),
                (float) $paginator->getTotalItems(),
            );
        }

        return $resources;
    }
}
