<?php

declare(strict_types=1);

namespace App\Application\Message\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Message\Model\MessageContext;
use App\Domain\Message\Repository\MessageContextRepositoryInterface;

final class FindMessageContextQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly MessageContextRepositoryInterface $messageContextRepository)
    {
    }

    public function __invoke(FindMessageContextQuery $query): ?MessageContext
    {
        return $this->messageContextRepository->find($query->id);
    }
}
