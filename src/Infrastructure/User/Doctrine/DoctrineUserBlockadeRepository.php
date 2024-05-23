<?php

namespace App\Infrastructure\User\Doctrine;

use App\Domain\User\Model\UserBlockade;
use App\Domain\User\Repository\UserBlockadeRepositoryInterface;
use App\Infrastructure\Shared\Doctrine\DoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserBlockadeRepository extends DoctrineRepository implements UserBlockadeRepositoryInterface
{
    private const ENTITY_CLASS = UserBlockade::class;
    private const ALIAS = 'user_blockade';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(UserBlockade $userBlockade): void
    {
        $this->em->persist($userBlockade);
        $this->em->flush();
    }

    public function remove(UserBlockade $userBlockade): void
    {
        $this->em->remove($userBlockade);
        $this->em->flush();
    }

    public function find(int $id): ?UserBlockade
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function findOneBy(array $params): ?UserBlockade
    {
        return parent::findOneByParams(self::ALIAS, $params);
    }
}
