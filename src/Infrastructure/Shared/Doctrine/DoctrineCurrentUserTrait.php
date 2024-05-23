<?php

namespace App\Infrastructure\Shared\Doctrine;

use App\Domain\User\Model\User;
use Doctrine\ORM\QueryBuilder;

trait DoctrineCurrentUserTrait
{
    public function currentUserFilter(User $user): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($user): void {
            $filterName = 'currentUser';
            $qb->andWhere(sprintf('%s.%s = :%s', self::ALIAS, 'user', $filterName))->setParameter(sprintf('%s', $filterName), $user->getId());
        });
    }
}
