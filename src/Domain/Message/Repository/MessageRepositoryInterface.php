<?php

declare(strict_types=1);

namespace App\Domain\Message\Repository;

use App\Domain\Message\Model\Message;

interface MessageRepositoryInterface
{
    public function save(Message $message): void;

    public function remove(Message $message): void;

    public function find(int $id): ?Message;

    public function findOneBy(array $params): ?Message;
}
