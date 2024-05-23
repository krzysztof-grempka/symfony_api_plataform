<?php

namespace App\Infrastructure\User\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Application\Shared\Query\QueryBusInterface;
use App\Application\User\Query\Me\FindMeQuery;
use App\Application\User\Service\UserHelperInterface;
use App\Domain\User\Model\User;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class MeProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly UserHelperInterface $userHelper,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$this->userHelper->getUserModel()) {
            throw new UnauthorizedHttpException('', 'JWT Token not found');
        }

        if ($operation instanceof CollectionOperationInterface) {
            return null;
        }

        /** @var User|null $model */
        $model = $this->queryBus->ask(new FindMeQuery());

        return UserResource::fromModel($model);
    }
}
