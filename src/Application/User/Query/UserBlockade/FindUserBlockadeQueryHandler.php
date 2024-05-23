<?php

namespace App\Application\User\Query\UserBlockade;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Model\UserBlockade;
use App\Domain\User\Repository\UserBlockadeRepositoryInterface;

class FindUserBlockadeQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserBlockadeRepositoryInterface $blockadeRepository
    ) {
    }

    public function __invoke(FindUserBlockadeQuery $query): ?UserBlockade
    {
        return $this->blockadeRepository->find($query->id);
    }
}
