<?php

declare(strict_types=1);

namespace App\Application\User\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;

final class FindUserQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(FindUserQuery $query): ?User
    {
        return $this->userRepository->find($query->id);
    }
}
