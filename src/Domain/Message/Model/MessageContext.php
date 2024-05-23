<?php

declare(strict_types=1);

namespace App\Domain\Message\Model;

use App\Domain\Shared\Model\ModelInterface;
use App\Domain\Shared\Traits\SoftDeleteableEntity;
use App\Domain\Shared\Traits\TimestampableEntity;
use App\Domain\User\Model\User;
use App\Infrastructure\Message\ApiPlatform\Resource\MessageContextResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: 'message_context')]
#[Gedmo\SoftDeleteable(fieldName: 'deleted', timeAware: false, hardDelete: true)]
class MessageContext implements ModelInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    public const RESOURCE = MessageContextResource::class;

    public const CREATED = 'created';
    public const QUEUE = 'queue';
    public const SENT = 'sent';
    public const ERROR = 'error';

    public const ALLOWED_STATUES = [
        self::CREATED,
        self::QUEUE,
        self::SENT,
        self::ERROR,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    public readonly ?int $id;

    #[ORM\Column(type: 'text', length: 255)]
    public string $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?\DateTimeImmutable $sentAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        public readonly User $sender,
        #[ORM\ManyToOne(targetEntity: User::class)]
        public readonly User $recipient,
        #[ORM\ManyToOne(targetEntity: Message::class, cascade: ['persist'])]
        public readonly Message $message,
    ) {
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        Assert::inArray($status, self::ALLOWED_STATUES);
        $this->status = $status;

        return $this;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getResource(): string
    {
        return self::RESOURCE;
    }
}
