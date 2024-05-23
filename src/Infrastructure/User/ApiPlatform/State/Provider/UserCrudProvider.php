<?php

declare(strict_types=1);

namespace App\Infrastructure\User\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Application\Shared\Query\QueryBusInterface;
use App\Application\User\Query\FindUserQuery;
use App\Application\User\Query\FindUsersQuery;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Shared\ApiFilter\FilterManager;
use App\Infrastructure\Shared\ApiPlatform\State\Paginator;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;

final class UserCrudProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly Pagination $pagination,
        private readonly FilterManager $filterManager,
        private array $filters = []
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof CollectionOperationInterface) {
            /** @var User|null $model */
            $model = $this->queryBus->ask(new FindUserQuery($uriVariables['id']));

            return null !== $model ? UserResource::fromModel($model) : null;
        }

        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }
        if (isset($context['filters'])) {
            $this->filters = $this->filterManager->getValidFilters($context['resource_class'], $context['filters']);
        }

        /** @var UserRepositoryInterface $models */
        $models = $this->queryBus->ask(new FindUsersQuery($offset, $limit, $this->filters));

        $resources = [];
        foreach ($models as $model) {
            $resources[] = UserResource::fromModel($model);
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
