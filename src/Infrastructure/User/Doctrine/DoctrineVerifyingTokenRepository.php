<?php

namespace App\Infrastructure\User\Doctrine;

use App\Domain\User\Model\VerifyingToken;
use App\Domain\User\Repository\VerifyingTokenRepositoryInterface;
use App\Infrastructure\Shared\Doctrine\DoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineVerifyingTokenRepository extends DoctrineRepository implements VerifyingTokenRepositoryInterface
{
    private const ENTITY_CLASS = VerifyingToken::class;
    private const ALIAS = 'VerifyingToken';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function generate(VerifyingToken $token): void
    {
        if ($oldTokens = $this->em->getRepository(self::ENTITY_CLASS)->findBy(['recipient' => $token->recipient])) {
            foreach ($oldTokens as $item) {
                self::removeUsed($item);
            }
        }

        $this->em->persist($token);
        $this->em->flush();
    }

    public function removeUsed(VerifyingToken $token): void
    {
        $this->em->remove($token);
        $this->em->flush();
    }

    public function verify(string $recipient): ?VerifyingToken
    {
        return $this->em->getRepository(self::ENTITY_CLASS)->findOneBy(['recipient' => $recipient]);
    }

    public function findOneBy(array $params): ?VerifyingToken
    {
        return parent::findOneByParams(self::ALIAS, $params);
    }
}
