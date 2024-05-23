<?php

declare(strict_types=1);

namespace App\Domain\Message\Repository;

use App\Domain\Message\Model\MessageContext;

interface MessageContextRepositoryInterface
{
    public function save(MessageContext $messageContext): void;

    public function remove(MessageContext $messageContext): void;

    public function find(int $id): ?MessageContext;

    public function withStatus(string $status): static;

    public function withPagination(int $page, int $itemsPerPage): static;

    public function saveBatch(int $sender, int $message, int ...$recipients): void;

    public function setStatusBatch(int $message, string $status): void;

    public function findOneBy(array $params): ?MessageContext;
}
