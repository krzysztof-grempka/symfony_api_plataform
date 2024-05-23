<?php

namespace App\Application\User\Query\UserBlockade;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Repository\UserBlockadeRepositoryInterface;

class FindUserBlockadeCollectionQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly UserBlockadeRepositoryInterface $blockadeRepository)
    {
    }

    public function __invoke(FindUserBlockadeCollectionQuery $query): UserBlockadeRepositoryInterface
    {
        $blockadeRepository = $this->blockadeRepository;

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $blockadeRepository = $this->blockadeRepository->withPagination($query->page, $query->itemsPerPage);
        }

        return $blockadeRepository;
    }
}
