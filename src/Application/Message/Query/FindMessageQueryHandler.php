<?php

declare(strict_types=1);

namespace App\Application\Message\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Message\Model\Message;
use App\Domain\Message\Repository\MessageRepositoryInterface;

final class FindMessageQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly MessageRepositoryInterface $messageRepository)
    {
    }

    public function __invoke(FindMessageQuery $query): ?Message
    {
        return $this->messageRepository->find($query->id);
    }
}
