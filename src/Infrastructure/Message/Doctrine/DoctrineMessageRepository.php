<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\Doctrine;

use App\Domain\Message\Model\Message;
use App\Domain\Message\Repository\MessageRepositoryInterface;
use App\Infrastructure\Shared\Doctrine\DoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineMessageRepository extends DoctrineRepository implements MessageRepositoryInterface
{
    private const ENTITY_CLASS = Message::class;
    private const ALIAS = 'msg';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Message $message): void
    {
        $this->em->persist($message);
        $this->em->flush();
    }

    public function remove(Message $message): void
    {
        $this->em->remove($message);
        $this->em->flush();
    }

    public function find(int $id): ?Message
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function findOneBy(array $params): ?Message
    {
        return parent::findOneByParams(self::ALIAS, $params);
    }
}
