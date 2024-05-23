<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Infrastructure\Message\ApiPlatform\State\Provider\MessageReadProvider;
use App\Infrastructure\Shared\ApiPlatform\Factory\ResourceFactory;
use App\Infrastructure\Shared\ApiPlatform\Resource\ResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'MessageContent',
    operations: [
        new Get(),
    ],
    security: "is_granted('ROLE_ADMIN')",
    provider: MessageReadProvider::class
)]
final class MessageResource implements ResourceInterface
{
    #[ApiProperty(writable: false, identifier: true)]
    public int $id;

    #[Assert\NotNull]
    #[Groups(['message.read'])]
    public ?string $subject;

    #[Assert\NotNull]
    #[Groups(['message.read'])]
    public string $body;

    public static function fromModel(object $model, array $excludedVars = []): object
    {
        return ResourceFactory::fromModel(self::class, $model, $excludedVars);
    }
}
