<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\Doctrine;

use App\Domain\Message\Model\MessageContext;
use App\Domain\Message\Repository\MessageContextRepositoryInterface;
use App\Infrastructure\Shared\Doctrine\DoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Workflow\WorkflowInterface;

final class DoctrineMessageContextRepository extends DoctrineRepository implements MessageContextRepositoryInterface
{
    private const ENTITY_CLASS = MessageContext::class;
    private const ALIAS = 'msgCxt';

    public function __construct(EntityManagerInterface $em, private WorkflowInterface $messageStateMachine)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(MessageContext $messageContext): void
    {
        $this->em->persist($messageContext);
        $this->em->flush();
    }

    public function remove(MessageContext $messageContext): void
    {
        $this->em->remove($messageContext);
        $this->em->flush();
    }

    public function find(int $id): ?MessageContext
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function saveBatch(int $sender, int $message, int ...$recipients): void
    {
        $tableName = $this->em->getClassMetadata(self::ENTITY_CLASS)->getTableName();
        $initialStatus = $this->messageStateMachine->getDefinition()->getInitialPlaces()[0];
        $conn = $this->em->getConnection();
        $currentDateTime = (new \DateTimeImmutable())->format('Y-m-d  H:i:s');
        $sql = sprintf('INSERT INTO %s (id, sender_id, recipient_id, message_id, status, created, updated) VALUES ', $tableName);
        foreach ($recipients as $key => $recipient) {
            $sql .= sprintf("(nextval('message_context_id_seq'), %d, %d, %d, '%s', '%s', '%s')", $sender, $recipient, $message, $initialStatus, $currentDateTime, $currentDateTime);
            if ($key !== array_key_last($recipients)) {
                $sql .= ', ';
            }
        }
        $sql .= ';';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
    }

    public function setStatusBatch(int $message, string $status): void
    {
        $this->query()->update(self::ENTITY_CLASS, self::ALIAS)
            ->set('msgCxt.status', ':status')
            ->set('msgCxt.sentAt', ':sentAt')
            ->where(sprintf('%s.message = :messageId', self::ALIAS))
            ->setParameter('status', $status)
            ->setParameter('messageId', $message)
            ->setParameter('sentAt', new \DateTimeImmutable())
            ->getQuery()
            ->execute();
    }

    public function withStatus(string $status): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($status): void {
            $qb->where(sprintf('%s.status = :status', self::ALIAS))->setParameter('status', $status);
        });
    }

    public function findOneBy(array $params): ?MessageContext
    {
        return parent::findOneByParams(self::ALIAS, $params);
    }
}
