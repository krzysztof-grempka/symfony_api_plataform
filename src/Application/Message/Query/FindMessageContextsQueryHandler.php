<?php

declare(strict_types=1);

namespace App\Application\Message\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Message\Repository\MessageContextRepositoryInterface;

final class FindMessageContextsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly MessageContextRepositoryInterface $messageContextRepository)
    {
    }

    public function __invoke(FindMessageContextsQuery $query): MessageContextRepositoryInterface
    {
        $messageContextRepository = $this->messageContextRepository;

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $messageContextRepository = $messageContextRepository->withPagination($query->page, $query->itemsPerPage);
        }

        return $messageContextRepository;
    }
}
