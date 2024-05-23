<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Doctrine;

use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Shared\Doctrine\DoctrineFiltersTrait;
use App\Infrastructure\Shared\Doctrine\DoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

final class DoctrineUserRepository extends DoctrineRepository implements UserRepositoryInterface
{
    use DoctrineFiltersTrait;

    private const ENTITY_CLASS = User::class;
    private const ALIAS = 'user';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function find(int $id): ?User
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function withFirstName(string $firstName): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($firstName): void {
            $qb->where(sprintf('%s.firstName = :firstName', self::ALIAS))->setParameter('firstName', $firstName);
        });
    }

    public function findOneByEmail(string $email): ?User
    {
        try {
            return $this->query()
                ->where(sprintf('%s.email = :email', self::ALIAS))
                ->setParameter('email', $email)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception) {
            return null;
        }
    }

    public function everyExist(int ...$ids): bool
    {
        $idsCount = count($ids);
        $dbCount = $this->query()
            ->select(sprintf('count(%s.id)', self::ALIAS))
            ->where(sprintf('%s.id in (:ids)', self::ALIAS))
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getSingleScalarResult();

        return $idsCount === $dbCount;
    }

    public function everyExistAndEnabled(int ...$ids): bool
    {
        $idsCount = count($ids);
        $dbCount = $this->query()
            ->select(sprintf('count(%s.id)', self::ALIAS))
            ->where(sprintf('%s.id in (:ids)', self::ALIAS))
            ->andWhere(sprintf('%s.enabled = true', self::ALIAS))
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getSingleScalarResult();

        return $idsCount === $dbCount;
    }

    public function getEmailsForIds(int ...$ids): array
    {
        return $this->query()->select(sprintf('%s.email', self::ALIAS))
            ->where(sprintf('%s.id in (:ids)', self::ALIAS))
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function getReference(int $id): ?User
    {
        return $this->em->getReference(self::ENTITY_CLASS, $id);
    }

    public function findOneBy(array $params): ?User
    {
        return parent::findOneByParams(self::ALIAS, $params);
    }

    public function findOneByIdentifier(int $identifier): ?User
    {
        return $this->query()
            ->join(sprintf('%s.data', self::ALIAS), 'userData')
            ->where('userData.identifier = :identifier')
            ->setParameter('identifier', $identifier)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNotVerifiedUsers(): array
    {
        return $this->query()
            ->where(sprintf('%s.verified = false', self::ALIAS))
            ->getQuery()
            ->getResult();
    }

    public function getPhoneNumbersForIds(int ...$ids): array
    {
        // TODO: Implement getPhoneNumbersForIds() method.

        return [];
    }
}
