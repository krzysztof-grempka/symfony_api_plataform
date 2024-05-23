<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Application\Message\Query\FindMessageContextQuery;
use App\Application\Message\Query\FindMessageContextsQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\Domain\Message\Model\MessageContext;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Message\ApiPlatform\Resource\MessageContextResource;
use App\Infrastructure\Shared\ApiPlatform\State\Paginator;

class MessageContextReadProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof CollectionOperationInterface) {
            /** @var MessageContext|null $model */
            $model = $this->queryBus->ask(new FindMessageContextQuery($uriVariables['id']));

            return null !== $model ? MessageContextResource::fromModel($model) : null;
        }

        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        /** @var UserRepositoryInterface $models */
        $models = $this->queryBus->ask(new FindMessageContextsQuery($offset, $limit));

        $resources = [];
        foreach ($models as $model) {
            $resources[] = MessageContextResource::fromModel($model);
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
