<?php

namespace App\Application\User\Query\Me;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\User\Service\UserHelperInterface;
use App\Domain\User\Model\User;

class FindMeQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserHelperInterface $userHelper,
    ) {
    }

    public function __invoke(FindMeQuery $query): ?User
    {
        return $this->userHelper->getUserModel();
    }
}
