<?php

namespace App\Domain\User\Repository;

use App\Domain\Shared\Repository\RepositoryInterface;
use App\Domain\User\Model\UserBlockade;

interface UserBlockadeRepositoryInterface extends RepositoryInterface
{
    public function save(UserBlockade $userBlockade): void;

    public function remove(UserBlockade $userBlockade): void;

    public function find(int $id): ?UserBlockade;

    public function findOneBy(array $params): ?UserBlockade;
}
